<?php

use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Driver\OrderController;
use App\Http\Controllers\Driver\ViewController;
use Illuminate\Support\Facades\Route;

// Route::middleware(['auth', 'verified'])->group(function () {

    Route::patch('/driver/deliver-order/{order}', [OrderController::class, 'deliver'])->name('driver.order.deliver');
    Route::patch('/driver/complete-order/{order}', [OrderController::class, 'complete_delivery'])->name('driver.order.complete_delivery');
    Route::get('/driver/delivery-history', [ViewController::class, 'delivery_history'])->name('driver.delivery_history');
    Route::resource('/driver/orders', OrderController::class)->names([
        'index' => 'driver.orders.index',
        'show' => 'driver.orders.show',
    ]);
    Route::get('/driver/dashboard', [ViewController::class, 'dashboard'])->name('driver.dashboard');
    // Route::get('/driver/inbox', [ViewController::class, 'inbox'])->name('driver.inbox');
    Route::get('/driver/profile', [ViewController::class, 'profile'])->name('driver.profile');
    Route::get('/driver/map', [ViewController::class, 'map'])->name('driver.map');

// });

