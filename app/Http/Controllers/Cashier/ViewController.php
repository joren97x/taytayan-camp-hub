<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Inertia\Inertia;

class ViewController extends Controller
{
    //
    public function dashboard() {
        return Inertia::render('Cashier/Dashboard');
    }

    public function profile(Request $request)
    {
        return Inertia::render('Cashier/Profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    public function inbox() {
        return Inertia::render('Cashier/Inbox');
    }

}
