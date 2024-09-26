<?php

namespace App\Http\Controllers\Customer\Product;

use App\Events\Product\OrderPending;
use App\Models\Cart;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Support\Facades\Log;
use Luigel\Paymongo\Facades\Paymongo;

class PaymentController extends Controller
{
    //
    public function success(Request $request, CartService $cartService) 
    {
        // dd(session('checkout_id'));
        // dd($checkout_session);
        // dd($request->get('cart_id'));
        $cart = Cart::find($request->cart_id);
        
        $order = Order::create([
            'user_id' => auth()->id(),
            'cart_id' => $cart->id,
            'status' => Order::STATUS_PENDING,
            'payment_method' => $request->payment_method,
            'mode' => $request->mode
        ]);

        if($request->payment_method == 'right_now') {
            $checkout_session = Paymongo::checkout()->find(session('checkout_id'));
            $order->payment_method = $checkout_session->payment_method_used;
            $order->save();
        }

        $result = $cartService->getCartLineItemsAndSubtotal($order->cart_id);
        $order->cart_products = $result['cart_products'];
        $order->subtotal = $result['subtotal'];

        Log::info($order);
        event(new OrderPending($order));

        // if ang cart kay active mag create bag-o then e inactive dayun!!!
        if($cart->status) {
            Cart::create([
                'user_id' => auth()->id()
            ]);
            $cart->status = false;
            $cart->update();
        }
      
        return redirect(route('customer.orders.index'));
    }

    public function pay(Request $request, CartService $cartService) 
    {

        if($request->payment_method != 'right_now') {
            // return $this->success($request->payment_method, $request->cart_id, $request->mode, $cartService);
            // return $this->success($request->mode, $request->payment_method, $request->cart_id, $cartService);
            return redirect(route('product.checkout.success') . '?' . http_build_query($request->all()));
        }

        $cart = Cart::find($request->cart_id);
        // dd($cart);
        $line_items = [];
        $subtotal = 0;
        // Fetch cart products with related product and modifiers
        $cart_products = $cart->cart_products()->with(['product', 'modifiers.modifier_item', 'modifiers.modifier_group'])->get();

        foreach($cart_products as $cart_product) {
            foreach($cart_product->modifiers as $modifier) {
                $modifier->total = $modifier->quantity * $modifier->modifier_item->price;
                $cart_product->product->price += $modifier->total;
            }
            $cart_product->total = $cart_product->quantity * $cart_product->product->price;
            $line_items[] = [
                'name' => $cart_product->product->name,
                'quantity' => $cart_product->quantity,
                'amount' => (double)$cart_product->product->price * 100,
                'currency' => 'PHP',
                'description' => $cart_product->product->description,
                'images' => [
                    'https://images.unsplash.com/photo-1613243555988-441166d4d6fd?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80'
                ],
            ];
            $subtotal += $cart_product->total;
        }

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

        session(['checkout_id' => $checkout->id]);

        return Inertia::location($checkout->checkout_url);
    }

}
