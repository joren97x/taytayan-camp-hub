<?php

namespace App\Http\Controllers\Customer\Product;

use App\Models\Cart;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Luigel\Paymongo\Facades\Paymongo;

class PaymentController extends Controller
{
    //
    public function success(Request $request, $mode, $payment_method) {

        $cart = Cart::where('user_id', auth()->id())->where('status', true)->firstOrFail();

        Order::create([
            'user_id' => auth()->user()->id,
            'cart_id' => $cart->id,
            'status' => Order::STATUS_PENDING,
            'payment_method' => $payment_method,
            'mode' => $mode
        ]);

        $cart->status = false;
        $cart->save();

        Cart::create([
            'user_id' => auth()->user()->id
        ]);



        return redirect('/orders');
    }

    public function pay(Request $request) {

        $cart = Cart::where('user_id', auth()->id())->where('status', true)->firstOrFail();
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
            'success_url' => route('product.checkout.success', 
                [
                    'mode' => $request->mode,
                    'payment_method' => $request->payment_method
                ]),
            'statement_descriptor' => 'Laravel Paymongo Library',
            'metadata' => [
                'Key' => 'Value'
            ]
        ]);
    
        return Inertia::location($checkout->checkout_url);
    }

}
