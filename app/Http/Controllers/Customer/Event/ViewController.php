<?php

namespace App\Http\Controllers\Customer\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ViewController extends Controller
{
    //
    public function checkout() {
        return Inertia::render('Customer/Event/Checkout', [
            'event' => 'hello'
        ]);
    }
}
