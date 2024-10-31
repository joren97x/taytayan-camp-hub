<?php

namespace App\Http\Controllers\Customer\Product;

use App\Events\Product\OrderPending;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    //
    // public function store(Request $request) 
    // {
    //     dd($request);
    //     $request->validate([
    //         'user_id' => 'required',
    //         'cart_id' => 'required',
    //         'status' => 'required',
    //         'payment_method' => 'required',
    //         'mode' => 'required'
    //     ]);

    //     $order = Order::create($request->all());
    //     event(new OrderPending($order));

    //     return back();

    // }

    // public function show(string $id)
    // {
    //     return Inertia::render('Customer/Product/ShowOrder', [
    //         'order' => Order::find($id)
    //     ]);
    // }

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

        $past_orders = Order::with('driver')
        ->whereIn('status', [
            Order::STATUS_CANCELLED,
            Order::STATUS_COMPLETED,
        ])
        ->where('user_id', auth()->id())
        ->get();

        $orders = collect([$active_orders, $past_orders]);

        $orders->each(function($order_list) use ($cartService) {
            foreach ($order_list as $order) {
                // Get cart products and subtotal
                $result = $cartService->getCartLineItemsAndSubtotal($order->cart_id);
                $order->cart_products = $result['cart_products'];
                $order->subtotal = $result['subtotal'];

                // Fetch conversation between user and driver if a driver is assigned
                if ($order->driver) {
                    $order->conversation_id = Conversation::with('participants')
                        ->whereHas('participants', function ($query) {
                            $query->where('user_id', auth()->id());
                        })
                        ->whereHas('participants', function ($query) use ($order) {
                            $query->where('user_id', $order->driver_id);
                        })
                        ->first();
                }
            }
        });

        // foreach($active_orders as $order) {
        //     $result = $cartService->getCartLineItemsAndSubtotal($order->cart_id);
        //     $order->cart_products = $result['cart_products'];
        //     $order->subtotal = $result['subtotal'];
        //     if($order->driver) {
        //         $order->conversation_id = Conversation::with('participants')->whereHas('participants', function ($query) {
        //             $query->where('user_id', auth()->id());
        //         })
        //         ->whereHas('participants', function ($query) use ($order) {
        //             $query->where('user_id', $order->driver_id);
        //         })
        //         ->first();
        //     }
        // }

        // foreach($past_orders as $order) {
        //     $result = $cartService->getCartLineItemsAndSubtotal($order->cart_id);
        //     $order->cart_products = $result['cart_products'];
        //     $order->subtotal = $result['subtotal'];
        //     if($order->driver) {
        //         $order->conversation_id = Conversation::with('participants')->whereHas('participants', function ($query) {
        //             $query->where('user_id', auth()->id());
        //         })
        //         ->whereHas('participants', function ($query) use ($order) {
        //             $query->where('user_id', $order->driver_id);
        //         })
        //         ->first();
        //     }
        // }

        return Inertia::render('Customer/Product/Orders', [
            'active_orders' => $active_orders,
            'past_orders' => $past_orders
        ]);

    }

    public function update(Order $order)
    {
        $order->status = Order::STATUS_COMPLETED;
        $order->update();
        return back();
    }

    public function show(string $id, CartService $cartService)
    {

        $order = Order::with('user')->find($id);

        if($order->user_id != auth()->id()) {
            abort(404);
        }

        $result = $cartService->getCartLineItemsAndSubtotal($order->cart_id);
        $order->cart_products = $result['cart_products'];
        $order->subtotal = $result['subtotal'];
        
        return Inertia::render('Customer/Product/ShowOrder', [
            'order' => $order
        ]);
    }

}
