<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ViewController extends Controller
{
    //
    public function dashboard() {


        return Inertia::render('Admin/Dashboard');
    }

    public function inbox()
    {
        return Inertia::render('Admin/Inbox', [
            'users' => User::where('role', '!=', User::ROLE_ADMIN)->get(),
            'conversations' => Conversation::with('messages')->whereHas('participants', function ($query) {
                $query->where('user_id', auth()->user()->id);
            })->get()
        ]);
    }

    public function user_management() {
        return Inertia::render('Admin/UserManagement');
    }
}
