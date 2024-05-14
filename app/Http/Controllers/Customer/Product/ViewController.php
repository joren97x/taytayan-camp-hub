<?php

namespace App\Http\Controllers\Customer\Product;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class ViewController extends Controller
{
    //
    public function index() {
        return Inertia::render('Customer/Product/Index', [
            'categories' => Category::with('products.modifier_groups.modifier_items')->get(),
            'products' => Product::with('modifier_groups.modifier_items')->get()
        ]);
    }

    public function orders() {
        return Inertia::render('Customer/Product/Orders');
    }

    public function checkout() {
        return Inertia::render('Customer/Product/Checkout');
    }
}
