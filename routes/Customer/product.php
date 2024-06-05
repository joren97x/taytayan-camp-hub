<?php

use App\Http\Controllers\Customer\Product\CartController;
use App\Http\Controllers\Customer\Product\OrderController;
use App\Http\Controllers\Customer\Product\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\Product\ViewController;


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/orders', [OrderController::class, 'on_progress'])->name('orders');
    Route::get('/on-progress-orders', [OrderController::class, 'on_progress'])->name('orders.on_progress');
    Route::get('/product-checkout', [ViewController::class, 'checkout'])->name('product.checkout');
    Route::resource('/cart', CartController::class)->names([
        'index' => 'customer.cart.index',
        'store' => 'customer.cart.store',
    ]);
    Route::get('/product-checkout-success/{mode}/{payment_method}', [PaymentController::class, 'success'])->name('product.checkout.success');
    Route::post('/product-pay', [PaymentController::class, 'pay'])->name('product.pay');
});

Route::get('/products', [ViewController::class, 'index'])->name('products');