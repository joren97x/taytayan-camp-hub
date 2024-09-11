<?php

namespace App\Http\Controllers\Customer\Product;

use App\Events\Product\OrderPending;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    //
    public function store(Request $request) 
    {
        $request->validate([
            'user_id' => 'required',
            'cart_id' => 'required',
            'status' => 'required',
            'payment_method' => 'required',
            'mode' => 'required'
        ]);

        $order = Order::create($request->all());
        event(new OrderPending($order));

        return back();

    }

    public function show(string $id)
    {
        return Inertia::render('Customer/Product/ShowOrder', [
            'order' => Order::find($id)
        ]);
    }

    public function index(Request $request, CartService $cartService) 
    {
        // if(strcmp($status, Order::STATUS_COMPLETED) == 0 || strcmp($status, Order::STATUS_CANCELLED) == 0) {
        //     $orders = Order::where('status', [$status])->where('user_id', auth()->user()->id)->get();
        // }
        // else {
        //     $orders = Order::whereIn('status', [
        //         Order::STATUS_PENDING,
        //         Order::STATUS_READY_FOR_DELIVERY,
        //         Order::STATUS_READY_FOR_PICKUP,
        //         Order::STATUS_DELIVERING,
        //         Order::STATUS_PREPARING,
        //     ])
        //     ->where('user_id', auth()->user()->id)
        //     ->get();
        // }

        $orders = Order::where('user_id', $request->user()->id)->get();

        foreach($orders as $order) {
            $result = $cartService->getCartLineItemsAndSubtotal($order->cart_id);
            $order->cart_products = $result['cart_products'];
            $order->subtotal = $result['subtotal'];
        }

        return Inertia::render('Customer/Product/Orders', [
            'orders' => $orders,
            'order_constants' => Order::getConstants()
        ]);

    }

    public function update(Order $order)
    {
        $order->status = Order::STATUS_COMPLETED;
        $order->update();
        return back();
    }

}
