<?php

namespace App\Http\Controllers\Admin\Product;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ModifierGroup;
use App\Models\ModifierGroupItem;
use App\Models\ModifierItem;

class ModifierGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        return Inertia::render('Admin/Product/ModifierGroups', 
        [
            'modifier_groups' => ModifierGroup::with('modifier_items')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render('Admin/Product/NewModifierGroup', ['modifier_items' => ModifierItem::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'items' => 'required',
        ]);

        $modifier_group = ModifierGroup::create($request->all());

        foreach ($request->items as $item) {
            ModifierGroupItem::create([
                'modifier_group_id' => $modifier_group->id,
                'modifier_item_id' => $item
            ]);
        }

        return redirect(route('admin.modifier_group.index', absolute: false));


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
