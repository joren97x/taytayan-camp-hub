<?php

namespace App\Http\Controllers\Customer\Product;

use App\Events\Product\OrderPending;
use App\Models\Cart;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CartProduct;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Support\Facades\Log;
use Luigel\Paymongo\Facades\Paymongo;

class PaymentController extends Controller
{
    //
    public function success(Request $request, CartService $cartService) 
    {

        $cart = Cart::find($request->cart_id);
        $payment_session = session('payment_session');
        
        if (!$payment_session) {
            return redirect(route('customer.orders.index'));
        }
        
        $order = Order::create([
            'user_id' => auth()->id(),
            'cart_id' => $cart->id,
            'status' => Order::STATUS_PENDING,
            'payment_method' => $request->payment_method,
            'mode' => $request->mode
        ]);

        if($request->payment_method == 'right_now') {
            $checkout_session = Paymongo::checkout()->find(session('checkout_id'));
            // dd($checkout_session);
            $order->update([
                'payment_method' => $checkout_session->payment_method_used,
                'payment_id' => $checkout_session->payments[0]['id'],
                'amount' => $checkout_session->payments[0]['attributes']['amount']
            ]);
        }

        event(new OrderPending($order));

        // if ang cart kay active mag create bag-o then e inactive dayun!!!
        if($cart->status) {
            $new_cart = Cart::create([
                'user_id' => auth()->id()
            ]);
            $cart->status = false;
            $cart->update();

            $cart_product_ids = array_column($request->cart_products, 'id');
            CartProduct::where('cart_id', $cart->id)
            ->whereNotIn('id', $cart_product_ids)
            ->update(['cart_id' => $new_cart->id]);
            
        }
        
        session()->forget('payment_session');
      
        return redirect(route('customer.orders.index'));
    }

    public function pay(Request $request, CartService $cartService) 
    {
        if($request->payment_method != 'right_now') {
            session(['payment_session' => true]);
            return redirect(route('product.checkout.success') . '?' . http_build_query($request->all()));
        }

        $cart = Cart::find($request->cart_id);
        $line_items = [];
        
        foreach($request->cart_products as $cart_product) {
            $base_price = (double)$cart_product['product']['price'];
            $modifiers_total = 0;
        
            if (isset($cart_product['modifiers'])) {
                foreach($cart_product['modifiers'] as $modifier) {
                    $modifiers_total += (double)$modifier['modifier_item']['price'];
                }
            }
        
            $line_items[] = [
                'name' => $cart_product['product']['name'],
                'quantity' => $cart_product['quantity'],
                'amount' => ($base_price + $modifiers_total) * 100, // Amount in cents
                'currency' => 'PHP',
                'description' => $cart_product['product']['description'],
                'images' => [
                    'https://images.unsplash.com/photo-1613243555988-441166d4d6fd?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80'
                ],
            ];
        }

        // dd($line_items);


        $checkout = Paymongo::checkout()->create([
            'cancel_url' => route('customer.cart.index'),
            'billing' => [
                'name' => $request->user()->first_name . ' ' . $request->user()->last_name,
                'email' => $request->user()->email,
                'phone' => $request->user()->phone_number,
            ],
            'description' => 'My checkout session description',
            'line_items' => $line_items,
            'payment_method_types' => [
                'atome',
                'billease',
                'card',
                'dob',
                'dob_ubp',
                'gcash',
                'grab_pay', 
                'paymaya'
            ],
            'success_url' => route('product.checkout.success') . '?' . http_build_query($request->all()),
            'statement_descriptor' => 'Laravel Paymongo Library',
            'metadata' =>  [
                'mode' => $request->mode,
                'payment_method' => $request->payment_method,
                'cart_id' => $cart->id
            ]
        ]);

        session(['payment_session' => true]);
        session(['checkout_id' => $checkout->id]);

        return Inertia::location($checkout->checkout_url);
    }

}
