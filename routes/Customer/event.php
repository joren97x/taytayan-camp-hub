<?php

use App\Http\Controllers\Customer\Event\EventController;
use App\Http\Controllers\Customer\Event\TicketController;
use App\Http\Controllers\Customer\Event\ViewController;
use Illuminate\Support\Facades\Route;

Route::get('/event-checkout', [ViewController::class, 'checkout'])->name('event.checkout');
Route::get('/tickets', [TicketController::class, 'index'])->name('tickets');
Route::resource('events', EventController::class)->names([
    'index' => 'customer.events.index',
    'show' => 'customer.events.show',
]);