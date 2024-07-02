<?php

use App\Models\Ticket;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id');
            $table->foreignId('user_id')->nullable();
            // $table->string('ticket_code')->unique();
            $table->enum('status', [
                Ticket::STATUS_AVAILABLE,
                Ticket::STATUS_RESERVED,
                Ticket::STATUS_SOLD,
                Ticket::STATUS_USED
            ])->default(Ticket::STATUS_AVAILABLE);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
