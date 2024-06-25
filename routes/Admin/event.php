<?php

use App\Http\Controllers\Admin\Event\EventController;
use App\Http\Controllers\Admin\Event\ViewController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;


// Route::get('/admin/events/{event}/dashboard', [EventController::class, 'event_dashboard'])->name('event.dashboard');
Route::get('/admin/events/reviews', [ViewController::class, 'reviews'])->name('event.reviews');
Route::resource('/admin/events', EventController::class)->names([
    'index' => 'admin.events.index',
    'create' => 'admin.events.create',
    'show' => 'admin.events.show',
    'edit' => 'admin.events.edit'
]);
