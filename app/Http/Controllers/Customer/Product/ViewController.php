<?php

namespace App\Http\Controllers\Customer\Product;

use App\Models\Cart;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CartProduct;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Support\Facades\Config;

class ViewController extends Controller
{
    //
    public function index() {
        return Inertia::render('Customer/Product/Index', [
            'categories' => Category::with('products.modifier_groups.modifier_items')->get(),
            'products' => Product::with('modifier_groups.modifier_items')->where('available', true)->get(),
            'featured_products' => Product::with('modifier_groups.modifier_items')
                ->where('is_featured', true)
                ->where('available', true)
                ->get()
        ]);
    }

    public function orders() {
        return Inertia::render('Customer/Product/Orders');
    }

    public function checkout(CartService $cartService, Request $request) {

        // $request->validate([
        //     'cart_id' => 'required'
        // ]);
        // dd($request);
        // dd($request);
        $request->validate([
            'cart_id' => 'required',
            'cart_products' => 'required|array|min:1', // Must be an array with at least one item
        ]);

        $cart_products = CartProduct::with(['product', 'modifiers.modifier_item', 'modifiers.modifier_group'])->whereIn('id', $request->query('cart_products'))->get();
        // dd($cart_products);
        // dd($request->query('cart_id'));
        // $cart_id = $cartService->getUserActiveCartId(auth()->user()->id);
        // $result = $cartService->getCartLineItemsAndSubtotal($request->query('cart_id'));
        
        return Inertia::render('Customer/Product/Checkout', [
            'cart_id' => $request->query('cart_id'),
            'order_constants' => Order::getConstants(),
            'cart_products'=> $cartService->sortCartProducts($cart_products), 
            'subtotal' => $cartService->computeSubtotal($cart_products),
            'google_maps_api_key' => config('app.google_maps_api_key')
        ]);

    }
}
