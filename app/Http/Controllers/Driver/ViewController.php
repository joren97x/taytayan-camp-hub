<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ViewController extends Controller
{
    //
    public function dashboard() 
    {
        return Inertia::render('Driver/Dashboard');
    }

    public function account() 
    {
        return Inertia::render('Driver/Account');
    }

    public function delivery_history(CartService $cartService) 
    {

        $orders = Order::whereIn('status', [Order::STATUS_DELIVERED, Order::STATUS_COMPLETED, Order::STATUS_CANCELLED])
        ->where('driver_id', auth()->id())
        ->with('user')
        ->latest()
        ->get();

        foreach($orders as $order) {
            $result = $cartService->getCartLineItemsAndSubtotal($order->cart_id);
            $order->cart_products = $result['cart_products'];
            $order->subtotal = $result['subtotal'];
            // $order->customer = $order->user()->get();
        }

        return Inertia::render('Driver/DeliveryHistory', [
            'orders' => $orders,
            'google_maps_api_key' => config('app.google_maps_api_key')
        ]);
    }

    public function profile(Request $request)
    {
        return Inertia::render('Driver/Profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'google_maps_api_key' => config('app.google_maps_api_key')
        ]);
    }

    public function inbox() 
    {
        return Inertia::render('Driver/Inbox');
    }

    public function map(string $id) 
    {
        return Inertia::render('Driver/Map', [
            'google_maps_api_key' => config('app.google_maps_api_key'),
            'order' => Order::with('user')->find($id)
        ]);
    }

}
