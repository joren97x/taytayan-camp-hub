<?php

namespace App\Http\Controllers\Admin\Product;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\ModifierGroup;
use App\Models\ModifierGroupProduct;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Inertia::render('Admin/Product/Products', 
        [
            'categories' => Category::all(),
            'products' => Product::with('categories')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render('Admin/Product/NewProduct', ['categories' => Category::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        dd($request);
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'photo' => 'required',
            'price' => 'required',
            'categories' => 'required'
        ]);

        $photo = $request->photo[0]->getClientOriginalName();
        $request->photo[0]->move(public_path('/images'), $photo);

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'photo' => $photo,
            'price' => $request->price
        ]);

        foreach ($request->categories as $category) {
            CategoryProduct::create([
                'product_id' => $product->id,
                'category_id' => $category
            ]);
        }

        return redirect(route('admin.product.index', absolute: false));

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
        return Inertia::render('Admin/Product/EditProduct', 
        [
            'categories' => Category::all(),
            'product' => Product::with(['categories', 'modifier_groups.modifier_items'])->find($id),
            'modifier_groups' => ModifierGroup::with('modifier_items')->get()
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'categories' => 'nullable',
        ]);

        $product = Product::find($id);
        CategoryProduct::where('product_id', $id)->delete();

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        
        foreach($request->categories as $category) {
            CategoryProduct::create([
                'product_id' => $product->id,
                'category_id' => $category,
            ]);
        }

        $product->save();

        return back();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function update_modifier_group(Request $request, string $id)
    {

        ModifierGroupProduct::where('product_id', $id)->delete();

        foreach ($request->modifier_group_ids as $modifier_group_id) {
            ModifierGroupProduct::create([
                'modifier_group_id' => $modifier_group_id,
                'product_id' => $id
            ]);
        }
        return back();
    }

    public function clear_modifier_group(Request $request, string $id)
    {
        ModifierGroupProduct::where('product_id', $id)->delete();
        return back();
    }

    public function update_photo(Request $request, string $id) {

        $request->validate([
            'photo' => 'required'
        ]);

        $photo = $request->photo[0]->getClientOriginalName();
        $request->photo[0]->move(public_path('/images'), $photo);

        $product = Product::find($id);

        $product->photo = $photo;
        $product->save();

        return back();

    }

}
