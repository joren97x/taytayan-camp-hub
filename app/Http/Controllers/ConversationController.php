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
        // dd(auth()->user());
        // return Inertia::render(ucfirst(auth()->user()->role) . '/Inbox');
        return Inertia::render('Chat/Index', ['role' => auth()->user()->role]);

    }

    public function get_conversations()
    {
        $conversations = Conversation::with('participants')->whereHas('participants', function ($query) {
            $query->where('user_id', auth()->id());
            })->get();

        return response()->json([
            'conversations' => $conversations
        ]);
    }

    public function get_conversation($id)
    {
        return response()->json([
            'conversations' => Conversation::with('participants')->whereHas('participants', function ($query) {
                $query->where('user_id', auth()->id());
            })->get(),
            'conversation' => Conversation::with('participants', 'messages.user')->find($id)
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
        // return Inertia::render(ucfirst(auth()->user()->role) . '/ShowChat');
        return Inertia::render('Chat/Show');
    }

    public function chat_user(User $user) 
    {
        // $existing_conversation = Conversation::whereHas('participants', function ($query) use ($order) {
        //     $query->where('user_id', $order->user_id);
        // })->whereHas('participants', function ($query) {
        //     $query->where('user_id', auth()->id());
        // })->first();

        // dd($user);

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

    public function chat_cashier() 
    {

        $active_recent_cashier = User::where('role', 'cashier')->where('is_online', true)->first();
        if (!$active_recent_cashier) {
            $active_recent_cashier = User::where('role', 'cashier')->latest('updated_at')->first();
        }


        $existing_conversation = Conversation::whereHas('participants', function ($query) use ($active_recent_cashier) {
            $query->where('user_id', $active_recent_cashier->id);
        })->whereHas('participants', function ($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$existing_conversation) {

            $conversation = Conversation::create();

            Participant::create([
                'user_id' => $active_recent_cashier->id,
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
