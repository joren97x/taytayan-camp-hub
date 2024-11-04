<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\ModifierGroupItem;
use App\Models\ModifierItem;
use Illuminate\Http\Request;

class ModifierItemController extends Controller
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
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required'
        ]);

        if(!$request->modifier_group_id) {
            return back();
        }

        //e update if ge update na nimo ang database kay nalimtan ang description OK
        // ModifierItem::create($request->all());
        $modifier_item = ModifierItem::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price
        ]);

        ModifierGroupItem::create([
            'modifier_group_id' => $request->modifier_group_id,
            'modifier_item_id' => $modifier_item->id
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
        $modifier_item = ModifierItem::find($id);
        $modifier_item->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price
        ]);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        ModifierItem::destroy($id);
        return back();
    }
}
