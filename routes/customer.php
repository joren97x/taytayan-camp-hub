<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\Product\ViewController;



Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/orders', [ViewController::class, 'orders'])->name('orders');
    Route::get('/product-checkout', [ViewController::class, 'checkout'])->name('product-checkout');

});

Route::get('/products', [ViewController::class, 'index'])->name('products');
