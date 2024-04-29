<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\MilkteaViewController;



Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/orders', [MilkteaViewController::class, 'orders'])->name('customer.orders');
    Route::get('/milktea-checkout', [MilkteaViewController::class, 'checkout'])->name('milktea-checkout');

});