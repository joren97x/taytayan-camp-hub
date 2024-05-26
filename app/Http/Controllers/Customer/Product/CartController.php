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

        return Inertia::render('Customer/Product/Cart', ['items'=> $cartItems, 'subtotal' => $subtotal]);
        // return response()->json([
        //     'items' => $cartItems,
        //     'subtotal' => $subtotal,
        // ]);
    }

    private function groupModifiersByGroup($modifiers)
    {
        $grouped = [];

        foreach ($modifiers as $modifier) {
            $group_id = $modifier->modifier_group_id;

            // Initialize group if not already
            if (!isset($grouped[$group_id])) {
                $grouped[$group_id] = [
                    'group' => $modifier->modifier_group,
                    'price' => 0.00,
                    'items' => []
                ];
            }

            $item = $modifier->modifier_item;
            $item->quantity = $modifier->quantity;
            $grouped[$group_id]['items'][] = $item;

            // Compute price for this modifier item
            $grouped[$group_id]['price'] += $item->quantity * $item->price;
        }
        return $grouped;
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
