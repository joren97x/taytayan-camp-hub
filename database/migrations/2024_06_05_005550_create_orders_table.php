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
            $table->foreignId('driver_id')->nullable();
            $table->decimal('delivery_fee')->nullable();
            $table->timestamp('waiting_time')->nullable();
            $table->decimal('total');
            $table->enum('status', [
                Order::STATUS_PENDING,
                Order::STATUS_PREPARING,
                Order::STATUS_READY_FOR_DELIVERY,
                Order::STATUS_DELIVERED,
                Order::STATUS_READY_FOR_PICKUP,
                Order::STATUS_DELIVERING,
                Order::STATUS_CANCELLED,
                Order::STATUS_COMPLETED
            ]);
            $table->string('payment_method');
            $table->string('payment_id')->nullable();
            $table->enum('mode', [
                Order::MODE_DELIVERY,
                Order::MODE_PICKUP
            ]);
            $table->timestamp('completed_at')->nullable();
            $table->softDeletes();
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
