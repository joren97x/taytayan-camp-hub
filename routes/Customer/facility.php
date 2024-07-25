<?php

use App\Http\Controllers\Customer\Facility\FacilityController;
use App\Http\Controllers\Customer\Facility\ReservationController;
use App\Http\Controllers\Customer\Facility\ViewController;
use Illuminate\Support\Facades\Route;


// Route::middleware(['auth', 'verified', 'customer'])->group(function () {
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations');
    Route::get('/facility-checkout', [ViewController::class, 'checkout'])->name('facility.checkout');
// });

Route::resource('facilities', FacilityController::class)->names([
    'index' => 'customer.facilities.index',
    'show' => 'customer.facilities.show',
]);


