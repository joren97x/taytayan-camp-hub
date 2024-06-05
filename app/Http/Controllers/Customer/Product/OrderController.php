<?php

namespace App\Http\Controllers\Customer\Product;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    //
    public function store(Request $request) {

        $request->validate([
            'user_id' => 'required',
            'cart_id' => 'required',
            'status' => 'required',
            'payment_method' => 'required',
            'mode' => 'required'
        ]);

        Order::create($request->all());

        return back();

    }

    public function on_progress() {

        $orders = Order::with(['user', 'cart', 'cart.cart_products.product'])->where('status',[
            Order::STATUS_PENDING,
            Order::STATUS_TO_BE_DELIVERED,
            Order::STATUS_FOR_DELIVERY,
            Order::STATUS_PREPARING,
        ])->get();

        return Inertia::render('Customer/Product/Orders', ['orders' => $orders]);

    }

}
