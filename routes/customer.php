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
use Inertia\Inertia;





Route::middleware('auth', 'customer')->group(function () {
    Route::get('/cart-length', [CartController::class, 'length'])->name('customer.cart.length');
    Route::resource('/cart', CartController::class)->names([
        'index' => 'customer.cart.index',
        'store' => 'customer.cart.store',
        'destroy' => 'customer.cart.destroy',
        'update' => 'customer.cart.update'
    ]);
    Route::put('/notifications/read', [NotificationController::class, 'read_notifications'])->name('customer.notifications.read_notifications');
    Route::put('/notifications/click/{notification}', [NotificationController::class, 'click'])->name('customer.notifications.click');
    Route::resource('/notifications', NotificationController::class)->names([
        'index' => 'customer.notifications.index',
        'destroy' => 'customer.notifications.destroy',
        'update' => 'customer.notifications.update'
    ]);
    Route::get('/profile', [ViewController::class, 'profile'])->name('customer.profile');
    Route::get('/profile/edit', [ViewController::class, 'edit_profile'])->name('customer.edit_profile');
});

Route::middleware(['auth', 'verified', 'customer'])->group(function () {

    Route::put('/add-address', [CustomerController::class, 'add_address'])->name('add-address');
    require __DIR__.'/Customer/product.php';
    require __DIR__.'/Customer/event.php';
    require __DIR__.'/Customer/facility.php';

});

