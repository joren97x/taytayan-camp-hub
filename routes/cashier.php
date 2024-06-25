<?php

use App\Http\Controllers\Cashier\OrderController;
use App\Http\Controllers\Cashier\ViewController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'cashier'])->group(function () {
    Route::get('/cashier/dashboard', [ViewController::class, 'dashboard'])->name('cashier.dashboard');
    Route::get('/cashier/orders', [OrderController::class, 'index'])->name('cashier.orders.index');
    Route::get('/cashier/account', [ViewController::class, 'account'])->name('cashier.account');
});

