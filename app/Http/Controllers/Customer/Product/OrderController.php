<?php

namespace App\Http\Controllers\Customer\Product;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;
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

    public function index(string $status, CartService $cartService) {
        
        if(strcmp($status, Order::STATUS_COMPLETED) == 0 || strcmp($status, Order::STATUS_CANCELLED) == 0) {
            $orders = Order::where('status', [$status])->where('user_id', auth()->user()->id)->get();
        }
        else {
            $orders = Order::whereIn('status', [
                Order::STATUS_PENDING,
                Order::STATUS_READY_FOR_DELIVERY,
                Order::STATUS_READY_FOR_PICKUP,
                Order::STATUS_DELIVERING,
                Order::STATUS_PREPARING,
            ])
            ->where('user_id', auth()->user()->id)
            ->get();
        }

        foreach($orders as $order) {
            $result = $cartService->getCartLineItemsAndSubtotal(false, $order->cart_id);
            $order->cart_products = $result['cart_products'];
            $order->subtotal = $result['subtotal'];
        }

        return Inertia::render('Customer/Product/Orders', ['orders' => $orders]);

    }

}
