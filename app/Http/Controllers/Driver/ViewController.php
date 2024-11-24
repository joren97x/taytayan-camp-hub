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
        $driverId = auth()->id();

        $ready_for_delivery_orders = Order::where('status', Order::STATUS_READY_FOR_DELIVERY)
        ->count();

        // Get the count of active deliveries
        $active_deliveries = Order::where('driver_id', $driverId)
            ->whereIn('status', [Order::STATUS_DELIVERING])
            ->count();

        // Get the total earnings from delivered orders
        $total_earnings = Order::where('driver_id', $driverId)
            ->whereIn('status', [Order::STATUS_DELIVERED, Order::STATUS_COMPLETED])
            ->sum('delivery_fee'); // or any other amount you want to include in earnings

        // Get recent completed orders
        $recent_delivery_orders = Order::with('user')
            ->where('driver_id', $driverId)
            ->latest()
            ->take(5)
            ->get(['id', 'status', 'total', 'user_id', 'payment_method', 'created_at']);
        
        $total_deliveries = Order::where('driver_id', $driverId)
            ->whereIn('status', [Order::STATUS_DELIVERED, Order::STATUS_COMPLETED])
            ->count(); // or any other amount you want to include in earnings

        $earnings_data = Order::where('driver_id', $driverId)
            ->whereIn('status', [Order::STATUS_DELIVERED, Order::STATUS_COMPLETED])
            ->selectRaw('DATE(created_at) as date, SUM(delivery_fee) as total_earnings')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    
            // return response()->json([
            //     'pendingOrders' => $pendingOrders,
            //     'activeDeliveries' => $activeDeliveries,
            //     'totalEarnings' => $totalEarnings,
            //     'recentOrders' => $recentOrders
            // ]);
        return Inertia::render('Driver/Dashboard', compact(
            'active_deliveries',
            'total_earnings',
            'ready_for_delivery_orders',
            'recent_delivery_orders',
            'earnings_data',
            'total_deliveries'
        ));
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
