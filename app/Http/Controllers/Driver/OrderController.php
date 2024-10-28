<?php

namespace App\Http\Controllers\Driver;

use App\Events\Product\OrderStatusUpdated;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Participant;
use App\Services\CartService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    //
    public function index(CartService $cartService) 
    {
        $orders = Order::where('status', [
            Order::STATUS_READY_FOR_DELIVERY
        ])->with('user')->get();

        foreach($orders as $order) {
            $result = $cartService->getCartLineItemsAndSubtotal($order->cart_id);
            $order->cart_products = $result['cart_products'];
            $order->subtotal = $result['subtotal'];
            // $order->customer = $order->user()->get();
        }

        return Inertia::render('Driver/Orders', ['orders' => $orders]);
    }

    public function active_deliveries(CartService $cartService)
    {
        $orders = Order::where('driver_id', auth()->id())
        ->where('status', Order::STATUS_DELIVERING)
        ->with('user')
        ->get();

        foreach($orders as $order) {
            $result = $cartService->getCartLineItemsAndSubtotal($order->cart_id);
            $order->cart_products = $result['cart_products'];
            $order->subtotal = $result['subtotal'];
            // $order->customer = $order->user()->get();
        }

        return Inertia::render('Driver/ActiveDeliveries', [
            'orders' => $orders
        ]);
    }

    public function deliver(Request $request, Order $order) 
    {
        $order->status = Order::STATUS_DELIVERING;
        $order->driver_id = auth()->id();
        $order->update();

        $existing_conversation = Conversation::whereHas('participants', function ($query) use ($order) {
            $query->where('user_id', $order->user_id);
        })->whereHas('participants', function ($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$existing_conversation) {

            $conversation = Conversation::create();

            Participant::create([
                'user_id' => $order->user_id,
                'conversation_id' => $conversation->id
            ]);

            Participant::create([
                'user_id' => auth()->id(),
                'conversation_id' => $conversation->id
            ]);
        }

        event(new OrderStatusUpdated($order, true, app(CartService::class)));
        
        Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Your Order Has Been Delivered',
            'description' => 'Your order has been successfully delivered!',
            'link' => route('customer.orders.show', $order->id),
        ]);

        return redirect(route('driver.active_deliveries'));

    }

    public function complete_delivery(Order $order)
    {
        $order->status = Order::STATUS_DELIVERED;
        $order->update();
        event(new OrderStatusUpdated($order, true, app(CartService::class)));

        return redirect(route('driver.orders.index'));
    }

    //might turn this into a service in the future cus its used to different controllers
    public function show(string $id, CartService $cartService)
    {
        $order = Order::with('user')->find($id);

        // $result = $cartService->getCartLineItemsAndSubtotal(false, $order->cart_id);
        // $order->cart_products = $result['cart_products'];
        // $order->subtotal = $result['subtotal'];

        $result = $cartService->getCartLineItemsAndSubtotal($order->cart_id);
        $order->cart_products = $result['cart_products'];
        $order->subtotal = $result['subtotal'];

        return response()->json($order);
    }

}
