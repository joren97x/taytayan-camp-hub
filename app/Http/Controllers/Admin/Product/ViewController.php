<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ViewController extends Controller
{
    //
    public function reviews() {
        return Inertia::render('Admin/Product/Reviews');
    }

    public function orders() {
        return Inertia::render('Admin/Product/Orders');
    }

}
