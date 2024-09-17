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

    public function inbox(string $id = null)
    {
        // dd(User::findOrFail($id));
        // dd($id);
        return Inertia::render('Customer/Inbox', [
            // 'users' => User::where('role', '!=', User::ROLE_ADMIN)
            'selected_user' => User::find($id),
            // 'users' => User::where('id', '!=', auth()->id())->get(),
            // 'conversations' => Conversation::with('messages')->whereHas('participants', function ($query) {
            //     $query->where('user_id', auth()->id());
            // })->get()
        ]);
    }

    // public function chat()
    // {
    //     return Inertia::render('Customer/Inbox', [

    //     ]);
    // }

}
