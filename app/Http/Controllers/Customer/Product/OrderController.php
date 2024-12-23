<?php

namespace App\Http\Controllers\Customer\Product;

use App\Events\Notify;
use App\Events\Product\OrderPending;
use App\Events\Product\OrderStatusUpdated;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
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

        $orders = Order::with('driver')
        ->where('user_id', auth()->id())
        ->withTrashed()
        ->latest()
        ->get();

        foreach ($orders as $order) {
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
            'orders' => $orders,
        ]);

    }

    public function update(Request $request, Order $order)
    {
        $order->update(['status' => Order::STATUS_COMPLETED, 'completed_at' => Carbon::now()]);

        $notification = Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Order Complete',
            'description' => 'Your Order Has Been Completed',
            'link' => route('customer.orders.show', $order->id),
        ]);

        event(new OrderStatusUpdated($order));
        event(new Notify($notification));

        return back();
    }

    public function cancel(Order $order)
    {
        $order->update(['status' => Order::STATUS_CANCELLED]);
        if(($order->payment_method != 'walk_in' && $order->payment_method != 'cash_on_delivery')) {
            $payment = Paymongo::payment()->find($order->payment_id);
            Paymongo::refund()->create([
                'amount' => $payment->amount,
                'reason' => \Luigel\Paymongo\Models\Refund::REASON_REQUESTED_BY_CUSTOMER, // `duplicate`, `fraudulent`, required by customer
                'payment_id' => $order->payment_id
            ]);
        }
        return back();
    }

    public function show(string $id, CartService $cartService)
    {

        $order = Order::with(['user', 'driver'])->find($id);

        if($order->user_id != auth()->id()) {
            abort(404);
        }

        $result = $cartService->getCartLineItemsAndSubtotal($order->cart_id);
        $order->cart_products = $result['cart_products'];
        $order->subtotal = $result['subtotal'];

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
        
        return Inertia::render('Customer/Product/ShowOrder', [
            'order' => $order
        ]);
    }

}
