<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ViewController extends Controller
{
    //
    public function dashboard() {
        return Inertia::render('Driver/Dashboard');
    }

    public function account() {
        return Inertia::render('Driver/Account');
    }

}
