<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ViewController extends Controller
{
    //
    public function home() {
        return Inertia::render('Customer/Index', [
            'products' => Product::with('modifier_groups.modifier_items')->get()
        ]);
    }
}
