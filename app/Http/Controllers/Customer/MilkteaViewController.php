<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MilkteaViewController extends Controller
{
    //
    public function index() {
        return Inertia::render('Customer/Milkteas/Index', ['milkteas' => 'hewwo']);
    }

    public function orders() {
        return Inertia::render('Customer/Milkteas/Orders', ['orders' => 'hewwo']);
    }

    public function checkout() {
        return Inertia::render('Customer/Milkteas/Checkout', ['orders' => 'hewwo']);
    }

}
