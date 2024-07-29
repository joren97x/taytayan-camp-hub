<?php

namespace App\Http\Controllers\Cashier;

use App\Events\Product\OrderReadyForDelivery;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    //
    public function index(CartService $cartService) 
    {
        $orders = Order::whereIn('status', [
            Order::STATUS_PENDING,
            Order::STATUS_READY_FOR_DELIVERY,
            Order::STATUS_READY_FOR_PICKUP,
            Order::STATUS_DELIVERING,
            Order::STATUS_PREPARING
        ])->with('user')->get();
        
        // dd($orders);
        
        foreach($orders as $order) {
            $result = $cartService->getCartLineItemsAndSubtotal(false, $order->cart_id);
            $order->cart_products = $result['cart_products'];
            $order->subtotal = $result['subtotal'];
        }

        return Inertia::render('Cashier/Orders', [
            'orders' => $orders,
            'order_constants' => Order::getConstants()
        ]);
    }

    public function show(string $id, CartService $cartService)
    {
        $order = Order::with('user')->find($id);
        // dd($order);
        $result = $cartService->getCartLineItemsAndSubtotal(false, $order->cart_id);
        $order->cart_products = $result['cart_products'];
        $order->subtotal = $result['subtotal'];

        return response()->json($order);
    }

    public function update_status(string $id, Request $request) 
    {
        $order = Order::find($id);

        // event(new OrderStatusUpdated($order));
        $request->validate([
            'status' => 'required'
        ]);
        
        switch($request->status) {
            case Order::STATUS_PREPARING:
                $order->status = Order::STATUS_PREPARING;
                $order->update();
                break;
            case Order::STATUS_DELIVERING:
                $order->status = Order::STATUS_DELIVERING;
                $order->update();
                break;
            case Order::STATUS_COMPLETED:
                $order->status = Order::STATUS_COMPLETED;
                $order->update();
                break;
            case Order::STATUS_READY_FOR_DELIVERY:
                $order->status = Order::STATUS_READY_FOR_DELIVERY;
                event(new OrderReadyForDelivery($order));
                $order->update();
                break;
            case Order::STATUS_READY_FOR_PICKUP:
                $order->status = Order::STATUS_READY_FOR_PICKUP;
                $order->update();
                break;
        }
        // event(new OrderStatusUpdated($order));

        return back();
    }

}
