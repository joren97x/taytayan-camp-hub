<?php

use App\Models\TicketOrder;
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
        Schema::create('ticket_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('event_id');
            $table->decimal('amount', 8, 2);
            $table->string('qr_code_path')->nullable();
            $table->enum('payment_method', [
                TicketOrder::PAYMENT_METHOD_CARD,
                TicketOrder::PAYMENT_METHOD_GCASH,
                TicketOrder::PAYMENT_METHOD_GRAB_PAY,
                TicketOrder::PAYMENT_METHOD_WALK_IN,
            ]);
            $table->enum('status', [
                TicketOrder::STATUS_COMPLETED,
                TicketOrder::STATUS_FAILED,
                TicketOrder::STATUS_PENDING,
            ])->default(TicketOrder::STATUS_PENDING);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_orders');
    }
};
