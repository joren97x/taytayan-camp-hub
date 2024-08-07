<?php

use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Driver\OrderController;
use App\Http\Controllers\Driver\ViewController;
use Illuminate\Support\Facades\Route;

// Route::middleware(['auth', 'verified'])->group(function () {

    Route::patch('/driver/deliver-order/{id}', [OrderController::class, 'deliver'])->name('driver.order.deliver');
    Route::resource('/driver/orders', OrderController::class)->names([
        'index' => 'driver.orders.index',
        'show' => 'driver.orders.show',
    ]);
    Route::get('/driver/dashboard', [ViewController::class, 'dashboard'])->name('driver.dashboard');
    Route::get('/driver/inbox', [ViewController::class, 'inbox'])->name('driver.inbox');
    Route::get('/driver/account', [ViewController::class, 'account'])->name('driver.account');
    Route::get('/driver/map', [ViewController::class, 'map'])->name('driver.map');

// });

