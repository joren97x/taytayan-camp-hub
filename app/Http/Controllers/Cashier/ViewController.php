<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Inertia\Inertia;

class ViewController extends Controller
{
    //
    public function dashboard()
    {
        return Inertia::render('Cashier/Dashboard');
    }

    public function profile(Request $request)
    {
        return Inertia::render('Cashier/Profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'google_maps_api_key' => config('app.google_maps_api_key')
        ]);
    }

    public function inbox() 
    {
        return Inertia::render('Cashier/Inbox', [
            'conversations' => Conversation::with('participants')->whereHas('participants', function ($query) {
                $query->where('user_id', auth()->id());
            })->get()
        ]);
    }

    public function show_conversation(User $user) 
    {
        // $existing_conversation = Conversation::whereHas('participants', function ($query) use ($order) {
        //     $query->where('user_id', $order->user_id);
        // })->whereHas('participants', function ($query) {
        //     $query->where('user_id', auth()->id());
        // })->first();

        $existing_conversation = Conversation::whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->whereHas('participants', function ($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$existing_conversation) {

            $conversation = Conversation::create();

            Participant::create([
                'user_id' => $user->id,
                'conversation_id' => $conversation->id
            ]);

            Participant::create([
                'user_id' => auth()->id(),
                'conversation_id' => $conversation->id
            ]);

            return redirect(route('conversations.show', $conversation->id));
        }

        return redirect(route('conversations.show', $existing_conversation->id));

    }

}
