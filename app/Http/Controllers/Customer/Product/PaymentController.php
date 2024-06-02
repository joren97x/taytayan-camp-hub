<?php

namespace App\Http\Controllers\Customer\Product;

use App\Models\Cart;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Luigel\Paymongo\Facades\Paymongo;

class PaymentController extends Controller
{
    //
    // public function pay(Request $request) {
    //     $checkout = Paymongo::checkout()->create([
    //         'cancel_url' => route('customer.cart.index'),
    //         'billing' => [
    //             'name' => $request->user()->name,
    //             'email' => $request->user()->email,
    //             'phone' => $request->user()->phone_number,
    //         ],
    //         'description' => 'My checkout session description',
    //         'line_items' => [
    //             [
    //                 'amount' => 10000,
    //                 'currency' => 'PHP',
    //                 'description' => 'Something of a product.',
    //                 'images' => [
    //                     'https://images.unsplash.com/photo-1613243555988-441166d4d6fd?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80'
    //                 ],
    //                 'name' => 'A payment card',
    //                 'quantity' => 1
    //             ],
    //             [
    //                 'amount' => 10000,
    //                 'currency' => 'PHP',
    //                 'description' => 'Something of a product.',
    //                 'images' => [
    //                     'https://images.unsplash.com/photo-1613243555988-441166d4d6fd?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80'
    //                 ],
    //                 'name' => 'A payment card',
    //                 'quantity' => 1
    //             ],
    //             [
    //                 'amount' => 10000,
    //                 'currency' => 'PHP',
    //                 'description' => 'Something of a product.',
    //                 'images' => [
    //                     'https://images.unsplash.com/photo-1613243555988-441166d4d6fd?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80'
    //                 ],
    //                 'name' => 'A payment card',
    //                 'quantity' => 1
    //             ]
    //         ],
    //         'payment_method_types' => [
    //             'atome',
    //             'billease',
    //             'card',
    //             'dob',
    //             'dob_ubp',
    //             'gcash',
    //             'grab_pay', 
    //             'paymaya'
    //         ],
    //         'success_url' => route('customer.cart.index'),
    //         'statement_descriptor' => 'Laravel Paymongo Library',
    //         'metadata' => [
    //             'Key' => 'Value'
    //         ]
    //     ]);
    //     dd($checkout);
    //     return redirect($checkout->checkout_url);
    // }

    public function sucess(Request $request) {
        return Inertia::render('Customer/Product/SuccessCheckout');
    }

    public function pay(Request $request) {

        $cart = Cart::where('user_id', auth()->id())->where('status', true)->firstOrFail();
        $subtotal = 0;
        $cartProducts = $cart->cart_products()->with(['product'])->get();

        // dd($cartProducts);
        $line_items = [];

        foreach ($cartProducts as $cartProduct) {
            $line_items[] = [
                'name' => $cartProduct->product->name,
                'quantity' => $cartProduct->quantity,
                'amount' => (double)$cartProduct->product->price * 100,
                'currency' => 'PHP',
                'description' => $cartProduct->product->description,
                'images' => [
                    'https://images.unsplash.com/photo-1613243555988-441166d4d6fd?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80'
                ],
            ];
        }
        $checkout = Paymongo::checkout()->create([
            'cancel_url' => route('customer.cart.index'),
            'billing' => [
                'name' => $request->user()->name,
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
            'success_url' => route('customer.cart.index'),
            'statement_descriptor' => 'Laravel Paymongo Library',
            'metadata' => [
                'Key' => 'Value'
            ]
        ]);
    
        dd($checkout);
        return redirect($checkout->checkout_url);
    }

}
