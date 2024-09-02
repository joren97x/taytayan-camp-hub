<?php

use App\Http\Controllers\Customer\Event\EventController;
use App\Http\Controllers\Customer\Event\PaymentController;
use App\Http\Controllers\Customer\Event\TicketController;
use App\Http\Controllers\Customer\Event\ViewController;
use Illuminate\Support\Facades\Route;

Route::get('/tickets', [TicketController::class, 'index'])->name('tickets');

Route::resource('events', EventController::class)->names([
    'index' => 'customer.events.index',
    'show' => 'customer.events.show',
]);

Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/event-checkout', [ViewController::class, 'checkout'])->name('event.checkout');
});

Route::post('/event-pay', [PaymentController::class, 'pay'])->name('event.pay');
Route::get('/event-checkout-success', [PaymentController::class, 'success'])->name('event.checkout.success');