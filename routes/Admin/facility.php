<?php

use App\Http\Controllers\Admin\Facility\FacilityController;
// use App\Http\Controllers\Admin\Facility\ReservationController;
use App\Http\Controllers\Admin\Facility\ViewController;
use App\Http\Controllers\Admin\Facility\BookingController;
use App\Http\Controllers\Admin\Facility\FacilityRatingController;
use Illuminate\Support\Facades\Route;

// Route::get('/admin/facilities/reservations', [ReservationController::class, 'index'])->name('admin.reservations.index');
Route::get('/bookings', [ViewController::class, 'bookings'])->name('admin.bookings.index');
Route::get('/dashboard/facilities', [ViewController::class, 'dashboard'])->name('admin.facilities.dashboard');
Route::resource('/facility/ratings', FacilityRatingController::class)->names([
    'index' => 'admin.facility_ratings.index'
]);
Route::post('/facilities/{facility}/update-images', [FacilityController::class, 'update_images'])->name('admin.facilities.update_images');
Route::resource('/facilities', FacilityController::class)->names([
    'index' => 'admin.facilities.index',
    'create' => 'admin.facilities.create',
    'show' => 'admin.facilities.show',
    'store' => 'admin.facilities.store',
    'edit' => 'admin.facilities.edit',
    'destroy' => 'admin.facilities.destroy',
    'update' => 'admin.facilities.update'
]);

