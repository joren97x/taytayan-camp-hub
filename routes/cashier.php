<?php

use App\Http\Controllers\Cashier\ConversationController;
use App\Http\Controllers\Cashier\OrderController;
use App\Http\Controllers\Cashier\TicketOrderController;
use App\Http\Controllers\Cashier\ViewController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'cashier'])->group(function () {
    Route::get('/cashier/dashboard', [ViewController::class, 'dashboard'])->name('cashier.dashboard');
    // Route::get('/cashier/orders', [OrderController::class, 'index'])->name('cashier.orders.index');
    Route::resource('/cashier/orders', OrderController::class)->names([
        'index' => 'cashier.orders.index',
        'show' => 'cashier.orders.show'
    ]);
    Route::get('/cashier/tickets', [TicketOrderController::class, 'index'])->name('cashier.tickets.index');
    Route::get('/cashier/account', [ViewController::class, 'account'])->name('cashier.account');
    Route::get('/cashier/inbox', [ViewController::class, 'inbox'])->name('cashier.inbox');
    Route::get('/cashier/inbox/{conversation}', [ConversationController::class, 'show'])->name('cashier.conversation.show');
    Route::get('/cashier/ticket-order/verify/{ticket_order_id}', [TicketOrderController::class, 'verify'])->name('cashier.ticket_order.verify');
    Route::patch('/cashier/orders/update-order-status/{id}', [OrderController::class, 'update_status'])->name('cashier.orders.update_status');
});

