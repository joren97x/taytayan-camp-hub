<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;
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

        return Inertia::render('Cashier/Orders', [
            'orders' => $orders,
            'order_constants' => Order::getConstants()
        ]);
    }
}
