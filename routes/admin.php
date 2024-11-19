<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ViewController;
use App\Http\Controllers\Admin\ConversationController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function() {

    Route::get('/dashboard', [ReportController::class, 'main_dashboard'])->name('admin.dashboard');
    // Route::get('/dashboard', [ViewController::class, 'dashboard'])->name('admin.dashboard');
    // Route::get('/inbox', [ConversationController::class, 'index'])->name('admin.inbox');
    // Route::get('/inbox/{conversation}', [ConversationController::class, 'show'])->name('admin.conversation.show');
    // Route::resource('/users', UserController::class)->names([
    //     'index' => 'admin.user.index',
    //     'show' => 'admin.user.show',
    //     'edit' => 'admin.user.edit'
    // ]);

    // Route::get('/user-management', [UserController::class, 'index'])->name('admin.user_management');
    // Route::post('/user-management', [UserController::class, 'store'])->name('admin.user_management.store');
    Route::resource('/users', UserController::class)->names([
        'index' => 'admin.users.index',
        'store' => 'admin.users.store',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);
    Route::get('/profile', [ViewController::class, 'profile'])->name('admin.profile');

    Route::get('/user-roles', [UserController::class, 'user_roles'])->name('admin.get_user_roles');
    require __DIR__.'/Admin/product.php';
    require __DIR__.'/Admin/event.php';
    require __DIR__.'/Admin/facility.php';

});