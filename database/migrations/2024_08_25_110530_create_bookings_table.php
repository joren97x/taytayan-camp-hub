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
            $table->decimal('total', 8, 2);
            $table->enum('status', [
                Booking::STATUS_CANCELLED,
                Booking::STATUS_CHECKED_IN,
                Booking::STATUS_CHECKED_OUT,
                Booking::STATUS_PENDING,
                Booking::STATUS_CONFIRMED
            ])->default(Booking::STATUS_PENDING);
            $table->enum('payment_method', [
                Booking::PAYMENT_METHOD_CARD,
                Booking::PAYMENT_METHOD_GCASH,
                Booking::PAYMENT_METHOD_GRAB_PAY,
                Booking::PAYMENT_METHOD_PAYMAYA,
                Booking::PAYMENT_METHOD_WALK_IN
            ]);
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
