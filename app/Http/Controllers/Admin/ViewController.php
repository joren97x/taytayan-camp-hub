<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ViewController extends Controller
{
    //
    public function dashboard() {
        return Inertia::render('Admin/Dashboard');
    }

    public function user_management() {
        return Inertia::render('Admin/UserManagement');
    }
}
