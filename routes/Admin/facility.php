<?php

use App\Http\Controllers\Admin\Facility\FacilityController;
// use App\Http\Controllers\Admin\Facility\ReservationController;
use App\Http\Controllers\Admin\Facility\ViewController;
use App\Http\Controllers\Admin\Facility\BookingController;
use Illuminate\Support\Facades\Route;

// Route::get('/admin/facilities/reservations', [ReservationController::class, 'index'])->name('admin.reservations.index');
Route::resource('/admin/bookings', BookingController::class)->names([
    'index' => 'admin.bookings.index'
]);

Route::post('/admin/facilities/{facility}/update-images', [FacilityController::class, 'update_images'])->name('admin.facilities.update_images');
Route::resource('/admin/facilities', FacilityController::class)->names([
    'index' => 'admin.facilities.index',
    'create' => 'admin.facilities.create',
    'show' => 'admin.facilities.show',
    'store' => 'admin.facilities.store',
    'edit' => 'admin.facilities.edit',
    'destroy' => 'admin.facilities.destroy',
    'update' => 'admin.facilities.update'
]);

