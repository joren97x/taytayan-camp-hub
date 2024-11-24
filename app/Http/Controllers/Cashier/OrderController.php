<?php

namespace App\Http\Controllers\Cashier;

use App\Events\Notify;
use App\Events\Product\OrderReadyForDelivery;
use App\Events\Product\OrderStatusUpdated;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;
use App\Services\CartService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Luigel\Paymongo\Facades\Paymongo;

class OrderController extends Controller
{
    //
    public function index(CartService $cartService) 
    {
        $orders = Order::with('driver')
        ->whereIn('status', [
            Order::STATUS_PENDING,
            Order::STATUS_READY_FOR_DELIVERY,
            Order::STATUS_READY_FOR_PICKUP,
            Order::STATUS_DELIVERING,
            Order::STATUS_PREPARING
        ])->with('user')->get();
        
        // dd($orders);
        
        foreach($orders as $order) {
            $result = $cartService->getCartLineItemsAndSubtotal($order->cart_id);
            $order->cart_products = $result['cart_products'];
            $order->subtotal = $result['subtotal'];
        }
        return Inertia::render('Cashier/Orders', [
            'google_maps_api_key' => config('app.google_maps_api_key'),
            'order_constants' => Order::getConstants()
        ]);
    }

    public function get_orders(CartService $cartService)
    {
        $orders = Order::with('driver')
        ->whereIn('status', [
            Order::STATUS_PENDING,
            Order::STATUS_READY_FOR_DELIVERY,
            Order::STATUS_READY_FOR_PICKUP,
            Order::STATUS_DELIVERING,
            Order::STATUS_PREPARING
        ])->with('user')->get();
        
        // dd($orders);
        
        foreach($orders as $order) {
            $result = $cartService->getCartLineItemsAndSubtotal($order->cart_id);
            $order->cart_products = $result['cart_products'];
            $order->subtotal = $result['subtotal'];
        }
        return response()->json($orders);
    }

    //might turn this into a service in the future cus its used to different controllers
    public function show(string $id, CartService $cartService)
    {
        $order = Order::with('user')->find($id);
        // dd($order);
        $result = $cartService->getCartLineItemsAndSubtotal($order->cart_id);
        $order->cart_products = $result['cart_products'];
        $order->subtotal = $result['subtotal'];

        return response()->json($order);
    }

    public function update_status(string $id, Request $request, CartService $cartService) 
    {

        $order = Order::find($id);
  
        $request->validate([
            'status' => 'required'
        ]);

        // $order->waiting_time = Carbon::now()->addMinutes($request->waiting_time);
        $order->status = $request->status;
        $order->update();

        if($request->status == Order::STATUS_READY_FOR_PICKUP) {
            $notification = Notification::create([
                'user_id' => $order->user_id,
                'title' => 'Order Ready To Be Picked Up',
                'description' => 'Your order is ready to be picked up from the store.',
                'link' => route('customer.orders.show', $order->id),
            ]);
    
            event(new Notify($notification));
        }

        // dd($order);
        // event(new OrderStatusUpdated($order, true, app(CartService::class)));
        event(new OrderStatusUpdated($order));
        if($order->mode == Order::MODE_DELIVERY && $request->status != Order::STATUS_CANCELLED) {
            event(new OrderReadyForDelivery($order));
        }
        if($request->status == Order::STATUS_CANCELLED && ($order->payment_method != 'walk_in' || $order->payment_method != 'cash_on_delivery')) {
            $payment = Paymongo::payment()->find($order->payment_id);
            Paymongo::refund()->create([
                'amount' => $payment->amount,
                'reason' => \Luigel\Paymongo\Models\Refund::REASON_OTHERS, // `duplicate`, `fraudulent`, required by customer
                'payment_id' => $order->payment_id
            ]);
        }

        return redirect(route('cashier.orders.index'));

    }

    public function prepare_order(Order $order, Request $request)
    {
        $local_time = Carbon::now()->setTimezone('Asia/Manila');

        $request->validate([
            'waiting_time' => 'required',
            'status' => 'required'
        ]);
        $order->waiting_time = $local_time->addMinutes((int)$request->waiting_time);
        $order->status = $request->status;
        $order->update();
        // event(new OrderStatusUpdated($order, true, app(CartService::class)));
        event(new OrderStatusUpdated($order));

        return back();
    }   

}
