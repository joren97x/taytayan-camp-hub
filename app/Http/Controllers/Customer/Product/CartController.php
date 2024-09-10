<?php

namespace App\Http\Controllers\Customer\Product;

use App\Models\Cart;
use Inertia\Inertia;
use App\Models\CartProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\CartService;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, CartService $cartService)
    {
        $cart = $cartService->getActiveCart(auth()->id());
        $result = $cartService->getCartLineItemsAndSubtotal($cart->id);

        return Inertia::render('Customer/Product/Cart', [
            'items'=> $result['cart_products'], 
            'subtotal' => $result['subtotal'],
            'google_maps_api_key' => config('app.google_maps_api_key'),
            'cart' => $cart
        ]);
        
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
        $validated_data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'cart_id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1',
            'special_instruction' => 'nullable|string',
            'modifiers' => 'nullable|array',
            'modifiers.*.modifier_group_id' => 'required_with:modifiers|exists:modifier_groups,id',
            'modifiers.*.modifier_item_id' => 'required_with:modifiers|exists:modifier_items,id',
            'modifiers.*.quantity' => 'required_with:modifiers|integer|min:1'
        ]);

        $cart_product = CartProduct::find($id);

        $cart_product->special_instruction = $validated_data['special_instruction'];
        $cart_product->quantity = $validated_data['quantity'];
        $cart_product->update();

        // Clear existing modifiers
        $cart_product->modifiers()->delete();

        // Add new modifiers
        if (!empty($validated_data['modifiers'])) {
            foreach ($validated_data['modifiers'] as $modifier) {
                $cart_product->modifiers()->create([
                    'modifier_group_id' => $modifier['modifier_group_id'],
                    'modifier_item_id' => $modifier['modifier_item_id'],
                    'quantity' => $modifier['quantity'],
                ]);
            }
        }

        return back();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        CartProduct::destroy($id);
        return back();
    }

    public function update_cart_item_quantity(Request $request, $id) {

        $cart_item = CartProduct::find($id);

        if($request->operation == '+') {
            $cart_item->quantity++;
        }
        else {
            $cart_item->quantity--;
        }

        $cart_item->update();

        return back();

    }

}
