<?php

use App\Http\Controllers\ConversationController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\NotificationController;
use App\Http\Controllers\Customer\ViewController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {

    Route::put('/add-address', [CustomerController::class, 'add_address'])->name('add-address');
    Route::get('/customer-notifications', [NotificationController::class, 'index'])->name('customer.notifications');
    Route::get('/inbox', [ViewController::class, 'inbox'])->name('customer.inbox');
});

require __DIR__.'/Customer/product.php';
require __DIR__.'/Customer/event.php';
