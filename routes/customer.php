<?php

use App\Http\Controllers\ConversationController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\NotificationController;
use App\Http\Controllers\Customer\ViewController;
use App\Http\Controllers\Customer\Event\EventController;
use App\Http\Controllers\Customer\Facility\FacilityController;
use App\Http\Controllers\Customer\Product\CartController;
use App\Http\Controllers\Customer\Product\ProductController;
use Illuminate\Support\Facades\Route;

Route::resource('events', EventController::class)->names([
    'index' => 'customer.events.index',
    'show' => 'customer.events.show',
]);

Route::resource('facilities', FacilityController::class)->names([
    'index' => 'customer.facilities.index',
    'show' => 'customer.facilities.show',
]);

Route::resource('products', ProductController::class)->names([
    'index' => 'customer.products.index',
    'show' => 'customer.products.show'
]);

Route::middleware('auth')->group(function () {
    Route::resource('/cart', CartController::class)->names([
        'index' => 'customer.cart.index',
        'store' => 'customer.cart.store',
        'destroy' => 'customer.cart.destroy',
        'update' => 'customer.cart.update'
    ]);
});

Route::middleware(['auth', 'verified', 'customer'])->group(function () {

    Route::put('/add-address', [CustomerController::class, 'add_address'])->name('add-address');
    Route::get('/customer-notifications', [NotificationController::class, 'index'])->name('customer.notifications');
    Route::get('/profile', [ViewController::class, 'profile'])->name('customer.profile');
    require __DIR__.'/Customer/product.php';
    require __DIR__.'/Customer/event.php';
    require __DIR__.'/Customer/facility.php';

});

