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
        dd($request);
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

    public function past_orders(CartService $cartService)
    {
        $orders = Order::whereIn('status', [
            Order::STATUS_COMPLETED,
            Order::STATUS_CANCELLED,
        ])
        ->where('user_id', auth()->id())
        ->get();

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

    public function index(Request $request, CartService $cartService, string $status = null) 
    {

        $active_orders = Order::with('driver')
        ->whereIn('status', [
            Order::STATUS_PENDING,
            Order::STATUS_READY_FOR_DELIVERY,
            Order::STATUS_DELIVERED,
            Order::STATUS_READY_FOR_PICKUP,
            Order::STATUS_DELIVERING,
            Order::STATUS_PREPARING,
        ])
        ->where('user_id', auth()->id())
        ->get();

        $past_orders = Order::whereIn('status', [
            Order::STATUS_CANCELLED,
            Order::STATUS_COMPLETED,
        ])
        ->where('user_id', auth()->id())
        ->get();

        foreach($active_orders as $order) {
            $result = $cartService->getCartLineItemsAndSubtotal($order->cart_id);
            $order->cart_products = $result['cart_products'];
            $order->subtotal = $result['subtotal'];
        }

        foreach($past_orders as $order) {
            $result = $cartService->getCartLineItemsAndSubtotal($order->cart_id);
            $order->cart_products = $result['cart_products'];
            $order->subtotal = $result['subtotal'];
        }

        return Inertia::render('Customer/Product/Orders', [
            'active_orders' => $active_orders,
            'past_orders' => $past_orders,
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
