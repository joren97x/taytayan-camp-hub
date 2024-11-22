<?php

use App\Http\Controllers\Admin\Event\EventController;
use App\Http\Controllers\Admin\Event\ViewController;
use App\Http\Controllers\Admin\Event\TicketController;
use App\Http\Controllers\Admin\UserController;
// use App\Http\Controllers\Customer\Event\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard/events', [ViewController::class, 'dashboard'])->name('admin.events.dashboard');
// Route::get('/admin/events/{event}/dashboard', [EventController::class, 'event_dashboard'])->name('event.dashboard');
Route::get('/admin/events/reviews', [ViewController::class, 'reviews'])->name('admin.events.reviews');
Route::patch('/admin/events/change-status/{event}', [EventController::class, 'change_status'])->name('admin.events.change_status');
Route::post('/admin/events/update-cover_photo/{id}', [EventController::class, 'update_cover_photo'])->name('admin.events.update_cover_photo');
Route::get('/admin/events/dashboard/{event}', [EventController::class, 'dashboard'])->name('admin.events.event_dashboard');
Route::resource('/admin/events', EventController::class)->names([
    'index' => 'admin.events.index',
    'create' => 'admin.events.create',
    'show' => 'admin.events.show',
    'store' => 'admin.events.store',
    'edit' => 'admin.events.edit',
    'destroy' => 'admin.events.destroy',
    'update' => 'admin.events.update'
]);

Route::resource('/admin/tickets', TicketController::class)->names([
    'index' => 'admin.tickets.index',
    'show' => 'admin.tickets.show',
    'create' => 'admin.tickets.create',
    'store' => 'admin.tickets.index',
    'update' => 'admin.tickets.update',
    'destroy' => 'admin.tickets.destroy',
    'edit' => 'admin.tickets.edit',
]);
