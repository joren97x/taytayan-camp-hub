<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json([
            'conversations' => Conversation::with('messages', 'participants')->whereHas('participants', function ($query) {
                $query->where('user_id', auth()->user()->id);
            })->get()
        ]);
        // return Inertia::render(ucwords(auth()->user()->role).'/Inbox/Index', [
        //     'conversations' => Conversation::with('participants')->whereHas('participants', function ($query) {
        //         $query->where('user_id', auth()->id());
        //     })->get(),
        // ]);

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
    public function store(Request $request)
    {
        //
        //could add validation like if user exists but nahh
        $request->validate([
            'message' => 'required',
            'receiver_id' => 'required'
        ]);

        $conversation = Conversation::create();

        Participant::create([
            'user_id' => $request->receiver_id,
            'conversation_id' => $conversation->id
        ]);

        Participant::create([
            'user_id' => auth()->id(),
            'conversation_id' => $conversation->id
        ]);

        Message::create([
            'user_id' => $request->receiver_id,
            'conversation_id' => $conversation->id,
            'message' => $request->message
        ]);


        // return redirect(route(auth()->user()->role.'conversations.show'));

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
        // return Inertia::render(ucwords(auth()->user()->role).'/Inbox/Show', [
        //     'conversation' => Conversation::with(['messages.user', 'participants'])->find($id)
        // ]);
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

    public function get_users_with_convo()
    {
        $conversations = Conversation::with(['participants' => function ($query) {
            $query->where('user_id', '!=', auth()->id());
        }])
        ->whereHas('participants', function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->get();
        return response()->json([
            'conversations' => $conversations,
        ]);
    }

    public function get_users()
    {
        return response()->json([
            'people' => User::where('id', '!=', auth()->id())->get()
        ]);
    }

}
