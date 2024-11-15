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
        ->get();

        foreach($orders as $order) {
            $result = $cartService->getCartLineItemsAndSubtotal($order->cart_id);
            $order->cart_products = $result['cart_products'];
            $order->subtotal = $result['subtotal'];
            // $order->customer = $order->user()->get();
        }

        return Inertia::render('Driver/DeliveryHistory', [
            'orders' => $orders
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

    public function map() 
    {
        return Inertia::render('Driver/Map', [
            'customers_coordinates' => [
                'lat' => 10.258557282636918, 
                'lng' => 124.04994738846034
            ],
            'stores_coordinates' => [
                'lat' => 10.25893392782387, 
                'lng' => 124.03877067362872
            ],
            'drivers_coordinates' => [
                'lat' => 10.24915105319012, 
                'lng' => 124.02561227312167
            ],
            'google_maps_api_key' => config('app.google_maps_api_key')
        ]);
    }

}
