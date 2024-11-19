<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ViewController extends Controller
{
    //
    public function reviews() {
        return Inertia::render('Admin/Product/Reviews');
    }

    public function dashboard() {

        $total_orders = Order::count();

        $orders_by_status = Order::selectRaw('status, COUNT(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status');

        $total_revenue = Order::where('status', 'completed')
        ->sum('total');

        $popular_products = Order::join('cart_products', 'orders.cart_id', '=', 'cart_products.cart_id')
        ->join('products', 'cart_products.product_id', '=', 'products.id') // Join the products table
        ->selectRaw('products.id as product_id, products.name, products.description, products.price, products.photo, SUM(cart_products.quantity) as quantity')
        ->groupBy('cart_products.product_id', 'products.id', 'products.name', 'products.description', 'products.photo', 'products.price') // Group by product details as well
        ->orderByDesc('quantity') // Order by the total quantity sold
        ->take(10) // Get top 10 products
        ->get();

        $orders_by_payment_method = Order::selectRaw('payment_method, COUNT(*) as count')
        ->groupBy('payment_method')
        ->pluck('count', 'payment_method');

        $orders_by_mode = Order::selectRaw('mode, COUNT(*) as count')
        ->groupBy('mode')
        ->pluck('count', 'mode');

        $featured_products = Product::where('is_featured', true)
        ->where('available', true)
        ->count();

        $average_order_value = Order::where('status', Order::STATUS_COMPLETED)
        ->average('total');

        $pending_orders = Order::whereIn('status', [
            Order::STATUS_PENDING,
            Order::STATUS_PREPARING,
            Order::STATUS_READY_FOR_DELIVERY,
            Order::STATUS_READY_FOR_PICKUP,
            Order::STATUS_DELIVERING,
        ])->count();

        $top_selling_product = Product::select('products.*')
            ->selectRaw('SUM(cart_products.quantity) as total_quantity')
            ->join('cart_products', 'products.id', '=', 'cart_products.product_id')
            ->groupBy('products.id', 'products.name', 'products.description', 'products.photo', 'products.price', 'products.is_featured', 'products.available', 'products.deleted_at', 'products.created_at', 'products.updated_at')
            ->orderByDesc('total_quantity')
            ->first();

        return Inertia::render('Admin/Product/Dashboard', compact(
            'total_orders',
            'total_revenue',
            'orders_by_status',
            'popular_products',
            'orders_by_payment_method',
            'orders_by_payment_method',
            'orders_by_mode',
            'featured_products',
            'top_selling_product',
            'average_order_value',
            'pending_orders'
        ));
    }

}
