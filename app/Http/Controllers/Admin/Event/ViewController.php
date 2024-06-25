<?php

namespace App\Http\Controllers\Admin\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ViewController extends Controller
{
    //
    public function reviews() {
        return Inertia::render('Admin/Event/Reviews');
    }
}
