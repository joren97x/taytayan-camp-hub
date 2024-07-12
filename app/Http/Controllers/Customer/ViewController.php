<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ViewController extends Controller
{
    //
    public function home() {
        return Inertia::render('Customer/Index', [
            'products' => Product::with('modifier_groups.modifier_items')->get()
        ]);
    }

    public function inbox()
    {
        return Inertia::render('Customer/Inbox', [
            // 'users' => User::where('role', '!=', User::ROLE_ADMIN)
            'users' => User::where('id', '!=', auth()->user()->id)->get(),
            'conversations' => Conversation::with('messages')->whereHas('participants', function ($query) {
                $query->where('user_id', auth()->user()->id);
            })->get()
        ]);
    }

}
