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
        //
        $cartId = $request->user()->id;
        dd($cartId);
        $cart = Cart::with('products.modifiers.modifierItem')->find($cartId);
        dd($cart);
        return Inertia::render('Customer/Product/Cart', ['products'=> $cart]);
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
        //
        
        // $data = $request->validate([
        //     'cart_id' => 'required|integer',
        //     'product_id' => 'required|integer',
        //     'quantity' => 'required|integer',
        //     'special_instructions' => 'nullable|string',
        //     'modifier_group_items' => 'required|array',
        // ]);

        // $modifierGroupIds = array_keys($data['modifier_group_items']);
        // $modifierItemIds = [];

        // foreach ($data['modifier_group_items'] as $groupId => $itemIds) {
        //     foreach ($itemIds as $itemId) {
        //         $modifierItemIds[] = $itemId;
        //     }
        // }

        // dd($modifierItemIds);
        // // Create a new CartProduct record
        // CartProduct::create([
        //     'cart_id' => $data['cart_id'],
        //     'product_id' => $data['product_id'],
        //     'quantity' => $data['quantity'],
        //     'special_instruction' => $data['special_instructions'],
        //     'modifier_group_ids' => json_encode($modifierGroupIds),
        //     'modifier_item_ids' => json_encode($modifierItemIds),
        // ]);
        // return back();

        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'cart_id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1',
            'special_instructions' => 'nullable|string',
            'modifiers' => 'nullable|array',
            'modifiers.*.modifier_group_id' => 'required_with:modifiers|exists:modifier_groups,id',
            'modifiers.*.modifier_item_id' => 'required_with:modifiers|exists:modifier_items,id',
            'modifiers.*.quantity' => 'required_with:modifiers|integer|min:1'
        ]);
        // Find or create the cart item
        $cartProduct = CartProduct::updateOrCreate(
            [
                'cart_id' => $validatedData['cart_id'],
                'product_id' => $validatedData['product_id'],
            ],
            [
                'quantity' => $validatedData['quantity'],
                'special_instructions' => $request->input('special_instructions', ''),
            ]
        );

        // Clear existing modifiers
        $cartProduct->modifiers()->delete();

        // Add new modifiers
        if (!empty($validatedData['modifiers'])) {
            foreach ($validatedData['modifiers'] as $modifier) {
                $cartProduct->modifiers()->create([
                    'modifier_group_id' => $modifier['modifier_group_id'],
                    'modifier_item_id' => $modifier['modifier_item_id'],
                    'quantity' => $modifier['quantity'],
                ]);
            }
        }

        return back();
        // return response()->json(['success' => true, 'message' => 'Cart item updated successfully', 'cartItem' => $cartProduct]);
    

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
