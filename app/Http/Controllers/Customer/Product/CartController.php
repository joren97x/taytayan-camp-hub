<?php

namespace App\Http\Controllers\Customer\Product;

use App\Models\Cart;
use Inertia\Inertia;
use App\Models\CartProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Fetch the cart for the authenticated user
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

        return Inertia::render('Customer/Product/Cart', ['items'=> $cart_products, 'subtotal' => $subtotal]);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'cart_id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1',
            'special_instruction' => 'nullable|string',
            'modifiers' => 'nullable|array',
            'modifiers.*.modifier_group_id' => 'required_with:modifiers|exists:modifier_groups,id',
            'modifiers.*.modifier_item_id' => 'required_with:modifiers|exists:modifier_items,id',
            'modifiers.*.quantity' => 'required_with:modifiers|integer|min:1'
        ]);
        // Find or create the cart item
        // $cartProduct = CartProduct::updateOrCreate(
        //     [
        //         'cart_id' => $validatedData['cart_id'],
        //         'product_id' => $validatedData['product_id'],
        //     ],
        //     [
        //         'quantity' => $validatedData['quantity'],
        //         'special_instructions' => $request->input('special_instructions', ''),
        //     ]
        // );

        $cartProduct = CartProduct::create(
            [
                'cart_id' => $validatedData['cart_id'],
                'product_id' => $validatedData['product_id'],
                'quantity' => $validatedData['quantity'],
                'special_instruction' => $validatedData['special_instruction']
            ]
        );
        // Clear existing modifiers
        // $cartProduct->modifiers()->delete();

        // Add new modifiers
        // if (!empty($validatedData['modifiers'])) {
            foreach ($validatedData['modifiers'] as $modifier) {
                $cartProduct->modifiers()->create([
                    'modifier_group_id' => $modifier['modifier_group_id'],
                    'modifier_item_id' => $modifier['modifier_item_id'],
                    'quantity' => $modifier['quantity'],
                ]);
            }
        // }

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
