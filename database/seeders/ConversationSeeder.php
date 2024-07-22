<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Participant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConversationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $convo = Conversation::create();

        Participant::create([
            'conversation_id' => $convo->id,
            'user_id' => 1
        ]);

        Participant::create([
            'conversation_id' => $convo->id,
            'user_id' => 2
        ]);

    }
}
