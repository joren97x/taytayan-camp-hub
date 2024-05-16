<?php

namespace App\Http\Controllers\Customer\Product;

use App\Http\Controllers\Controller;
use App\Models\CartProduct;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        dd($request->all());
        $request->validate([
            'product_id' => 'required',
            'cart_id' => 'required',
            'quantity' => 'required'
        ]);

        CartProduct::create([
            'cart_id' => $request->cart_id,
            'product_id' => $request->product_id,
            'modifier_group_ids' => $request->modifier_group_ids,
            'modifier_item_ids' => $request->modifier_item_ids,
            'special_instruction' => $request->special_instruction,
            'quantity' => $request->quantity
        ]);

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
