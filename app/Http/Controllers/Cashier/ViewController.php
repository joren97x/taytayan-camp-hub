<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Inertia\Inertia;

class ViewController extends Controller
{
    //
    public function dashboard() {
        return Inertia::render('Cashier/Dashboard');
    }

    public function account() {
        return Inertia::render('Cashier/Account');
    }

    public function inbox() {
        return Inertia::render('Cashier/Conversations');
    }

}
