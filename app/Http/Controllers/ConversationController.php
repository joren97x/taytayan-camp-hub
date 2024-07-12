<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json([
            'conversations' => Conversation::with('messages')->whereHas('participants', function ($query) {
                $query->where('user_id', auth()->user()->id);
            })->get()
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $user_id)
    {
        //
        //could add validation like if user exists but nahh
        $request->validate([
            'message' => 'required'
        ]);

        $conversation = Conversation::create();

        Participant::create([
            'user_id' => $user_id,
            'conversation_id' => $conversation->id
        ]);

        Participant::create([
            'user_id' => $request->user()->id,
            'conversation_id' => $conversation->id
        ]);

        Message::create([
            'user_id' => $user_id,
            'conversation_id' => $conversation->id,
            'message' => $request->message
        ]);

        return back();

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        // $authUserId = Auth::id();
        // $userId = 3;
        // $conversation = Conversation::with('messages')->whereHas('participant', function ($query) {
        //     $query->where('user_id', auth()->user()->id);
        //     })->whereHas('participant', function ($query) use ($user_id) {
        //         $query->where('user_id', $user_id);
        //     })->first();
        $conversation = Conversation::with(['messages.user', 'participants'])->find($id);
        return response()->json(['conversation' => $conversation]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
