<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
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

    public function profile(Request $request)
    {
        return Inertia::render('Customer/Profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    public function inbox()
    {
        return Inertia::render('Customer/Inbox', [
            'conversations' => Conversation::with('participants')->whereHas('participants', function ($query) {
                $query->where('user_id', auth()->id());
            })->get()
        ]);
    }

    public function show_conversation(string $id)
    {
        return Inertia::render('Customer/ShowChat', [
            'conversations' => Conversation::with('participants')->whereHas('participants', function ($query) {
                $query->where('user_id', auth()->id());
            })->get(),
            'conversation' => Conversation::with('participants', 'messages.user')->find($id)
        ]);
    }

    // public function chat()
    // {
    //     return Inertia::render('Customer/Inbox', [

    //     ]);
    // }

}
