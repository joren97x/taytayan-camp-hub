<?php

use App\Models\Reservation;
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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id');
            $table->foreignId('user_id');
            $table->decimal('total', 8, 2);
            $table->enum('status', [
                Reservation::STATUS_CANCELLED,
                Reservation::STATUS_CHECKED_IN,
                Reservation::STATUS_CHECKED_OUT,
                Reservation::STATUS_PENDING,
                Reservation::STATUS_CONFIRMED
            ]);
            $table->enum('payment_method', [
                Reservation::PAYMENT_METHOD_CARD,
                Reservation::PAYMENT_METHOD_GCASH,
                Reservation::PAYMENT_METHOD_GRAB_PAY,
                Reservation::PAYMENT_METHOD_PAYMAYA,
                Reservation::PAYMENT_METHOD_WALK_IN
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
        Schema::dropIfExists('reservations');
    }
};
