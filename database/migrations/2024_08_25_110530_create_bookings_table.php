<?php

use App\Models\Booking;
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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id');
            $table->foreignId('user_id');
            $table->decimal('total');
            $table->enum('status', [
                Booking::STATUS_CANCELLED,
                Booking::STATUS_CHECKED_IN,
                Booking::STATUS_CHECKED_OUT,
                Booking::STATUS_COMPLETE,
                Booking::STATUS_PENDING,
                Booking::STATUS_CONFIRMED
            ])->default(Booking::STATUS_PENDING);
            $table->string('payment_method');
            $table->string('payment_id')->nullable();
            $table->timestamp('check_in')->nullable();
            $table->timestamp('check_out')->nullable();
            $table->integer('guests');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
