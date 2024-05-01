<?php

namespace App\Http\Controllers\Customer\Product;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ViewController extends Controller
{
    //
    public function index() {
        return Inertia::render('Customer/Product/Index');
    }

    public function orders() {
        return Inertia::render('Customer/Product/Orders');
    }

    public function checkout() {
        return Inertia::render('Customer/Product/Checkout');
    }
}
