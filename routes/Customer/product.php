<?php

use App\Http\Controllers\Customer\Product\CartController;
use App\Http\Controllers\Customer\Product\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\Product\ViewController;


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/orders', [ViewController::class, 'orders'])->name('orders');
    Route::get('/product-checkout', [ViewController::class, 'checkout'])->name('product.checkout');
    Route::resource('/cart', CartController::class)->names([
        'index' => 'customer.cart.index',
        'store' => 'customer.cart.store',
    ]);
    Route::get('/success-checkout', [PaymentController::class, 'sucess']);
    Route::post('/product-pay', [PaymentController::class, 'pay'])->name('product.pay');
});

Route::get('/products', [ViewController::class, 'index'])->name('products');