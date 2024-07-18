<?php

use App\Http\Controllers\Customer\Facility\FacilityController;
use App\Http\Controllers\Customer\Facility\ReservationController;
use Illuminate\Support\Facades\Route;


// Route::middleware(['auth', 'verified', 'customer'])->group(function () {
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations');
// });

Route::resource('facilities', FacilityController::class)->names([
    'index' => 'customer.facilities.index',
    'show' => 'customer.facilities.show',
]);


