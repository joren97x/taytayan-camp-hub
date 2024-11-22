<?php

use App\Http\Controllers\Cashier\BookingController;
// use App\Http\Controllers\Cashier\ConversationController;
use App\Http\Controllers\Cashier\OrderController;
use App\Http\Controllers\Cashier\TicketController;
use App\Http\Controllers\Cashier\TicketOrderController;
use App\Http\Controllers\Cashier\ViewController;
use App\Http\Controllers\ConversationController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'cashier'])->group(function () {
    Route::patch('/cashier/orders/prepare-order/{order}', [OrderController::class, 'prepare_order'])->name('cashier.orders.prepare_order');
    Route::get('/cashier/dashboard', [ViewController::class, 'dashboard'])->name('cashier.dashboard');
    // Route::patch('/cashier/orders/{order}', [OrderController::class, 'prepare_order'])->name('cashier.nigga');
    // Route::get('/cashier/orders', [OrderController::class, 'index'])->name('cashier.orders.index');
    Route::get('/cashier/get-orders', [OrderController::class, 'get_orders'])->name('cashier.orders.get_orders');
    Route::resource('/cashier/orders', OrderController::class)->names([
        'index' => 'cashier.orders.index',
        'show' => 'cashier.orders.show',
        'create' => 'cashier.orders.create',
        'store' => 'cashier.orders.show',
        'destroy' => 'cashier.orders.destroy',
        'edit' => 'cashier.orders.edit',
        'update' => 'cashier.orders.update',
    ]);

    Route::get('/cashier/chat-user/{user}', [ViewController::class, 'show_conversation'])->name('cashier.conversations.show');
    Route::patch('/cashier/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('cashier.bookings.cancel');
    Route::patch('/cashier/bookings/{booking}/check-in', [BookingController::class, 'check_in'])->name('cashier.bookings.check_in');
    Route::patch('/cashier/bookings/{booking}/check-out', [BookingController::class, 'check_out'])->name('cashier.bookings.check_out');
    Route::resource('/cashier/bookings', BookingController::class)->names([
        'index' => 'cashier.bookings.index',
        'show' => 'cashier.bookings.show',
        'create' => 'cashier.bookings.create',
        'index' => 'cashier.bookings.index',
        'store' => 'cashier.bookings.store',
        'destroy' => 'cashier.bookings.destroy',
        'update' => 'cashier.bookings.update',
    ]);
    Route::get('/cashier/profile', [ViewController::class, 'profile'])->name('cashier.profile');
    // Route::get('/cashier/inbox', [ViewController::class, 'inbox'])->name('cashier.inbox');
    // Route::get('/cashier/inbox/{conversation}', [ViewController::class, 'show_conversation'])->name('cashier.conversations.show');

    // Route::get('/cashier/events/{event}', [TicketOrderController::class, 'dashboard'])->name('cashier.events.dashboard');
    Route::get('/cashier/events/check-in/{event}', [TicketOrderController::class, 'check_in'])->name('cashier.events.check_in');
    Route::patch('/cashier/tickets/undo/{ticket}', [TicketController::class, 'undo'])->name('cashier.tickets.undo');
    // Route::get('/cashier/tickets', [TicketOrderController::class, 'index'])->name('cashier.tickets.index');
    Route::resource('/cashier/tickets', TicketController::class)->names([
        'index' => 'cashier.tickets.index',
        'update' => 'cashier.tickets.update',
        'store' => 'cashier.tickets.store',
        'destroy' => 'cashier.tickets.destroy',
        'create' => 'cashier.tickets.create',
        'show' => 'cashier.tickets.store',
        'edit' => 'cashier.tickets.edit',
    ]);
    Route::get('/cashier/ticket-order/verify/{ticket_order_id}', [TicketOrderController::class, 'verify'])->name('cashier.ticket_order.verify');
    Route::patch('/cashier/orders/update-order-status/{id}', [OrderController::class, 'update_status'])->name('cashier.orders.update_status');
});

