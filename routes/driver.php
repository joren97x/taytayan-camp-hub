<?php

use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Driver\OrderController;
use App\Http\Controllers\Driver\ViewController;
use Illuminate\Support\Facades\Route;

// Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/driver/orders', [OrderController::class, 'index'])->name('driver.orders.index');
    Route::get('/driver/dashboard', [ViewController::class, 'dashboard'])->name('driver.dashboard');
    Route::get('/driver/account', [ViewController::class, 'account'])->name('driver.account');
    Route::get('/driver/map', [ViewController::class, 'map'])->name('driver.map');

// });

