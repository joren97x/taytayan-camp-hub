<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ViewController extends Controller
{
    //
    public function dashboard() {
        return Inertia::render('Admin/Dashboard');
    }

    public function chat()
    {
        return Inertia::render('Admin/Chat', [
            'users' => User::where('role', '!=', User::ROLE_ADMIN)->get()
        ]);
    }

    public function user_management() {
        return Inertia::render('Admin/UserManagement');
    }
}
