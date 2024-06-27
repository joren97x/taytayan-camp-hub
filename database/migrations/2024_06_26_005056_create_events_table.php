<?php

use App\Models\Event;
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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('cover_photo');
            $table->string('location');
            $table->json('coords')->nullable();
            $table->date('date');
            $table->time('start_time');
            $table->integer('capacity');
            $table->integer('min_ticket')->default(1);
            $table->integer('max_ticket')->default(1);
            $table->decimal('admission_fee', 8, 2);
            $table->integer('tickets_sold')->default(0);
            $table->enum('status', [
                Event::STATUS_DRAFT,
                Event::STATUS_PAST_EVENT,
                Event::STATUS_UPCOMING_EVENT
            ])->default(Event::STATUS_UPCOMING_EVENT);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
