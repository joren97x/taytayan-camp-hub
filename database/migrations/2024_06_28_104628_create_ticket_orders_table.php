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
            $table->string('payment_method');
            // $table->enum('status', [
            //     TicketOrder::STATUS_COMPLETED,
            //     TicketOrder::STATUS_CANCELLED,
            //     TicketOrder::STATUS_SCANNED,
            //     TicketOrder::STATUS_CONFIRMED,
            //     TicketOrder::STATUS_PENDING,
            // ])->default(TicketOrder::STATUS_PENDING);
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
