<?php

use App\Http\Controllers\Customer\Event\EventController;
use App\Http\Controllers\Customer\Event\PaymentController;
use App\Http\Controllers\Customer\Event\TicketController;
use App\Http\Controllers\Customer\Event\ViewController;
use Illuminate\Support\Facades\Route;


Route::put('/tickets/cancel/{ticket_order}', [TicketController::class, 'cancel'])->name('customer.tickets.cancel');
Route::resource('/profile/tickets', TicketController::class)->names([
    'index' => 'customer.tickets.index',
    'show' => 'customer.tickets.show',
    'create' => 'customer.tickets.create',
    'store' => 'customer.tickets.store',
    'edit' => 'customer.tickets.edit',
    'update' => 'customer.tickets.update',
    'destroy' => 'customer.tickets.destroy',
]);
Route::get('/event-checkout', [ViewController::class, 'checkout'])->name('event.checkout');
Route::post('/event-pay', [PaymentController::class, 'pay'])->name('event.pay');
Route::get('/event-checkout-success', [PaymentController::class, 'success'])->name('event.checkout.success');