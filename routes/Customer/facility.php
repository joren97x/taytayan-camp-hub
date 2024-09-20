<?php

use App\Http\Controllers\Customer\Facility\BookingController;
use App\Http\Controllers\Customer\Facility\FacilityController;
use App\Http\Controllers\Customer\Facility\PaymentController;
use App\Http\Controllers\Customer\Facility\ReservationController;
use App\Http\Controllers\Customer\Facility\ViewController;
use Illuminate\Support\Facades\Route;


// Route::middleware(['auth', 'verified', 'customer'])->group(function () {
    // Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations');
    Route::get('/facility-checkout', [ViewController::class, 'checkout'])->name('facility.checkout');
    Route::post('/facility-pay', [PaymentController::class, 'pay'])->name('facility.pay');
    // Route::post('/facility-sucess', [PaymentController::class, 'pay'])->name('facility.pay');
    Route::get('/facility-checkout-success', [PaymentController::class, 'success'])->name('facility.checkout.success');
// });

Route::resource('bookings', BookingController::class)->names([
    'index' => 'customer.bookings.index'
]);

Route::resource('facilities', FacilityController::class)->names([
    'index' => 'customer.facilities.index',
    'show' => 'customer.facilities.show',
]);


