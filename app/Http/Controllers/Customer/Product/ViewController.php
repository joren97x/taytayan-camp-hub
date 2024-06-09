<?php

namespace App\Http\Controllers\Customer\Product;

use App\Models\Cart;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;

class ViewController extends Controller
{
    //
    public function index() {
        return Inertia::render('Customer/Product/Index', [
            'categories' => Category::with('products.modifier_groups.modifier_items')->get(),
            'products' => Product::with('modifier_groups.modifier_items')->get()
        ]);
    }

    public function orders() {
        return Inertia::render('Customer/Product/Orders');
    }

    public function checkout(CartService $cartService) {

        $cart_id = $cartService->getUserActiveCartId(auth()->user()->id);
        $result = $cartService->getCartLineItemsAndSubtotal(true, $cart_id);
        
        return Inertia::render('Customer/Product/Checkout', [
            'order_constants' => Order::getConstants(),
            'items'=> $result['cart_products'], 
            'subtotal' => $result['subtotal'],
            'google_maps_api_key' => env('GOOGLE_MAPS_API_KEY')
        ]);

    }
}
