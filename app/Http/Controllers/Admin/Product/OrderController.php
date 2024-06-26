<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    //
    
    public function index(CartService $cartService) {
        
        $orders = Order::whereIn('status', [
            Order::STATUS_PENDING,
            Order::STATUS_READY_FOR_DELIVERY,
            Order::STATUS_READY_FOR_PICKUP,
            Order::STATUS_DELIVERING,
            Order::STATUS_PREPARING
        ])->with('user')->get();

        foreach($orders as $order) {
            $result = $cartService->getCartLineItemsAndSubtotal(false, $order->cart_id);
            $order->cart_products = $result['cart_products'];
            $order->subtotal = $result['subtotal'];
        }
        return Inertia::render('Admin/Product/Orders', [
            'orders' => $orders,
            'order_constants' => Order::getConstants()
        ]);
    }

    public function update_status(Request $request, string $id) {

        $request->validate([
            'status' => 'required'
        ]);
        
        $order = Order::find($id);
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
                $order->update();
                break;
            case Order::STATUS_READY_FOR_PICKUP:
                $order->status = Order::STATUS_READY_FOR_PICKUP;
                $order->update();
                break;
        }
        return back();
    }

}
