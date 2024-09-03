<?php

namespace App\Http\Controllers\Customer\Product;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    //
    public function show($id) 
    {
        return Product::with('modifier_groups.modifier_items')->find($id);
    }

    public function index()
    {
        return Inertia::render('Customer/Product/Index', [
            'categories' => Category::with('products.modifier_groups.modifier_items')->get(),
            'products' => Product::with('modifier_groups.modifier_items')->get(),
            'featured_products' => Product::with('modifier_groups.modifier_items')->where('is_featured', true)->get(),
            'google_maps_api_key' => config('app.google_maps_api_key')
        ]);
    }

}
