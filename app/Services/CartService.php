<?php
namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartService
{

    public function getActiveCart($user_id) {
        return Cart::where('status', true)->where('user_id', $user_id)->first();
    }

    public function getCartLineItemsAndSubtotal($cart_id)
    {
        
        // $cart = Cart::where('user_id', $user_id)
        $cart = Cart::find($cart_id);
        // ->firstOrFail();

        // Initialize subtotal
        $subtotal = 0;

        // Fetch cart products with related product and modifiers
        // $cart_products = $cart->cart_products()->with(['product', 'modifiers.modifier_item', 'modifiers.modifier_group'])->get();
        $cart_products = $cart->cart_products()
        ->with([
            'product' => function($query) {
                $query->withTrashed();
            }, 
            'modifiers.modifier_item', 
            'modifiers.modifier_group' => function($query) {
                $query->withTrashed();
            }
        ])
        ->get();
        
        foreach ($cart_products as $cart_product) {
            $product_price = $cart_product->product->price; // Store the base price
            $modifiers_total = 0;
        
            foreach ($cart_product->modifiers as $modifier) {
                $modifiers_total += $modifier->quantity * $modifier->modifier_item->price;
            }
        
            $cart_product->total = $cart_product->quantity * ($product_price + $modifiers_total);
            $subtotal += $cart_product->total;
        }
        // dd($cart_products);
        // foreach($cart_products as $cart_product) {
        //     foreach($cart_product->modifiers as $modifier) {
        //         $modifier->total = $modifier->quantity * $modifier->modifier_item->price;
        //         $cart_product->product->price += $modifier->total;
        //     }
        //     $cart_product->total = $cart_product->quantity * $cart_product->product->price;
        //     $subtotal += $cart_product->total;
        // }
        // dd($cart_products);
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

        return [
            'cart_products' => $cart_products,
            'subtotal' => $subtotal,
        ];
    }

    public function sortCartProducts($cart_products)
    {
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

        return $cart_products;
    }

    public function computeSubtotal($cart_products)
    {
        $subtotal = 0;

        // Fetch cart products with related product and modifiers
        // $cart_products = $cart->cart_products()->with(['product', 'modifiers.modifier_item', 'modifiers.modifier_group'])->get();

        // foreach($cart_products as $cart_product) {
        //     foreach($cart_product->modifiers as $modifier) {
        //         $modifier->total = $modifier->quantity * $modifier->modifier_item->price;
        //         $cart_product->product->price += $modifier->total;
        //     }
        //     $cart_product->total = $cart_product->quantity * $cart_product->product->price;
        //     $subtotal += $cart_product->total;
        // }

        foreach ($cart_products as $cart_product) {
            $product_price = $cart_product->product->price; // Store the base price
            $modifiers_total = 0;
        
            foreach ($cart_product->modifiers as $modifier) {
                $modifiers_total += $modifier->quantity * $modifier->modifier_item->price;
            }
        
            $cart_product->total = $cart_product->quantity * ($product_price + $modifiers_total);
            $subtotal += $cart_product->total;
        }

        return $subtotal;
    }

}
