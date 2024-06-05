<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Order;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('cart_id');
            $table->enum('status', [
                Order::STATUS_PENDING,
                Order::STATUS_PREPARING,
                Order::STATUS_FOR_DELIVERY,
                Order::STATUS_TO_BE_DELIVERED,
                Order::STATUS_CANCELLED,
                Order::STATUS_COMPLETED
            ]);
            $table->enum('payment_method', [
                Order::PAYMENT_METHOD_GCASH,
                Order::PAYMENT_METHOD_CREDIT,
                Order::PAYMENT_METHOD_PAYMAYA,
                Order::PAYMENT_METHOD_GRAB_PAY,
                Order::PAYMENT_METHOD_WALK_IN,
                Order::PAYMENT_METHOD_CASH_ON_DELIVERY
            ]);
            $table->enum('mode', [
                Order::MODE_DELIVERY,
                Order::MODE_PICKUP
            ]);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
