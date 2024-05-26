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
        $cartProducts = $cart->cart_products()->with(['product', 'modifiers.modifier_item', 'modifiers.modifier_group'])->get();

        // Prepare the response data
        $cartItems = $cartProducts->map(function ($cartProduct) use (&$subtotal) {
            $product = $cartProduct->product;

            // Group modifiers by modifier group
            $modifiersGroupedByGroup = $cartProduct->modifiers->groupBy('modifier_group_id')->map(function ($modifiers, $groupId) {
                $modifierGroup = $modifiers->first()->modifier_group;
                return [
                    'modifier_group_id' => $groupId,
                    'modifier_group_name' => $modifierGroup->name,
                    'modifiers' => $modifiers->map(function ($modifier) {
                        $modifierItem = $modifier->modifier_item;
                        return [
                            'modifier_item_id' => $modifier->modifier_item_id,
                            'modifier_item_name' => $modifierItem->name,
                            'modifier_item_price' => $modifierItem->price,
                            'quantity' => $modifier->quantity,
                            'total_modifier_price' => $modifierItem->price * $modifier->quantity,
                        ];
                    }),
                ];
            });

            // Calculate the total price for the cart product including modifiers
            $totalProductPrice = ($product->price * $cartProduct->quantity) + $modifiersGroupedByGroup->sum(function ($group) {
                return $group['modifiers']->sum('total_modifier_price');
            });

            // Add to subtotal
            $subtotal += $totalProductPrice;
            return [
                'product_id' => $cartProduct->product_id,
                'product_name' => $product->name,
                'product_photo' => $product->photo,
                'product_price' => $product->price,
                'quantity' => $cartProduct->quantity,
                'special_instruction' => $cartProduct->special_instruction,
                'modifier_groups' => $modifiersGroupedByGroup->values(),
                'total_product_price' => $totalProductPrice,
            ];
        });

        return Inertia::render('Customer/Product/Checkout', 
        [
            'items' => $cartItems,
            'subtotal' => $subtotal
        ]);
    }
}
