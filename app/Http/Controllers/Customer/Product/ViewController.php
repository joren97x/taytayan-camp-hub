<?php

namespace App\Http\Controllers\Customer\Product;

use App\Models\Cart;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    public function checkout() {

        $cart = Cart::where('user_id', auth()->id())->where('status', true)->firstOrFail();

        // Initialize subtotal
        $subtotal = 0;

        // Fetch cart products with related product and modifiers
        $cart_products = $cart->cart_products()->with(['product', 'modifiers.modifier_item', 'modifiers.modifier_group'])->get();

        foreach($cart_products as $cart_product) {
            foreach($cart_product->modifiers as $modifier) {
                $modifier->total = $modifier->quantity * $modifier->modifier_item->price;
                $cart_product->product->price += $modifier->total;
            }
            $cart_product->total = $cart_product->quantity * $cart_product->product->price;
            $subtotal += $cart_product->total;
        }

        // // Prepare the response data
        $cart_products->map(function ($cart_product) {
        //     // Group modifiers by modifier group
            $modifiersGroupedByGroup = $cart_product->modifiers->groupBy('modifier_group_id')->map(function ($modifiers) {
                $modifier_group = $modifiers->first()->modifier_group;
                return [
                    'modifier_group' => $modifier_group,
                    'modifier_items' => $modifiers->map(function ($modifier) {

                        return $modifier;
                    }),
                ];
            });
            $cart_product->grouped_modifiers = $modifiersGroupedByGroup;
        });
        return Inertia::render('Customer/Product/Checkout', ['items'=> $cart_products, 'subtotal' => $subtotal]);

    }
}
