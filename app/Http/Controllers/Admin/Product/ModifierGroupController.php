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
            'modifier_groups' => ModifierGroup::with(['modifier_items', 'products'])->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render('Admin/Product/CreateModifierGroup', ['modifier_items' => ModifierItem::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
       
        $request->validate([
            'name' => 'required|string|max:255',
            'modifier_items' => 'required|array|min:1',
            'required_quantity' => 'required|numeric|min:1', // Ensures required_quantity is at least 1
            'max_quantity' => 'required|numeric|min:1',
        ]);

        $modifier_group = ModifierGroup::create([
            'name' => $request->name,
            'required' => $request->required,
            'required_quantity' => $request->required_quantity,
            'max_quantity' => $request->max_quantity
        ]);

        foreach ($request->modifier_items as $item) {
            $modifier_item = ModifierItem::create([
                'name' => $item['name'],
                'description' => $item['description'],
                'price' => $item['price']
            ]);
    
            ModifierGroupItem::create([
                'modifier_group_id' => $modifier_group->id,
                'modifier_item_id' => $modifier_item->id
            ]);
        }

        return redirect(route('admin.modifier_groups.index', absolute: false));

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
        return Inertia::render('Admin/Product/EditModifierGroup', [
            'modifier_items' => ModifierItem::all(),
            'modifier_group' => ModifierGroup::with('modifier_items')->find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'modifier_items' => 'required',
        ]);

        $modifier_group = ModifierGroup::find($id);
        $modifier_group->name = $request->name;
        $modifier_group->required = $request->required;
        $modifier_group->required_quantity = $request->required_quantity;
        $modifier_group->max_quantity = $request->required_quantity;

        $modifier_group->update();
        ModifierGroupItem::where('modifier_group_id', $modifier_group->id)->delete();
        // $modifier_group->modifier_items()->delete();

        foreach ($request->modifier_items as $item) {
            ModifierGroupItem::create([
                'modifier_group_id' => $modifier_group->id,
                'modifier_item_id' => $item
            ]);
        }

        return redirect(route('admin.modifier_groups.index', absolute: false));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        ModifierGroup::destroy($id);
        return back();
    }
}
