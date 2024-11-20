<?php

use App\Http\Controllers\Customer\Product\CartController;
use App\Http\Controllers\Customer\Product\OrderController;
use App\Http\Controllers\Customer\Product\PaymentController;
use App\Http\Controllers\Customer\Product\ProductController;
use App\Http\Controllers\Customer\Product\ProductRatingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\Product\ViewController;


// Route::middleware(['auth', 'verified', 'customer'])->group(function () {

    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
    // Route::get('/orders/{status}', [OrderController::class, 'index'])->name('orders');
    // Route::get('/order/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/profile/past-orders', [OrderController::class, 'past_orders'])->name('customer.orders.past_orders');

    Route::patch('/profile/orders/cancel-order/{order}', [OrderController::class, 'cancel'])->name('customer.orders.cancel');
    Route::resource('/profile/orders', OrderController::class)->names([
        'show' => 'customer.orders.show',
        'index' => 'customer.orders.index',
        'update' => 'customer.orders.update'
    ]);
    
    Route::get('/on-progress-orders', [OrderController::class, 'on_progress'])->name('orders.on_progress');
    Route::get('/product-checkout', [ViewController::class, 'checkout'])->name('product.checkout');
    Route::get('/order-checkout', [ViewController::class, 'checkout'])->name('customer.checkout');
    // Route::resource('/cart', CartController::class)->names([
    //     'index' => 'customer.cart.index',
    //     'store' => 'customer.cart.store',
    //     'destroy' => 'customer.cart.destroy',
    //     'update' => 'customer.cart.update'
    // ]);
    Route::put('/cart/update-cart-quantity/{id}', [CartController::class, 'update_cart_item_quantity'])->name('customer.cart.update_cart_item_quantity');
    Route::get('/product-checkout-success', [PaymentController::class, 'success'])->name('product.checkout.success');
    Route::post('/product-pay', [PaymentController::class, 'pay'])->name('product.pay');
    Route::post('/product-rating', [ProductRatingController::class, 'store'])->name('customer.product_rating.store');

// });

// Route::get('/products', [ViewController::class, 'index'])->name('products')->middleware('customer');
// Route::resource('products', ProductController::class)->names([
//     'index' => 'customer.products.index',
//     'show' => 'customer.products.show'
// ]);