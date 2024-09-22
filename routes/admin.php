<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ViewController;
use App\Http\Controllers\Admin\ConversationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->group(function() {
    Route::get('/admin/dashboard', [ViewController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/inbox', [ConversationController::class, 'index'])->name('admin.conversation.index');
    Route::get('/admin/inbox/{conversation}', [ConversationController::class, 'show'])->name('admin.conversation.show');
    Route::resource('/admin/users', UserController::class)->names([
        'index' => 'admin.user.index',
        'show' => 'admin.user.show',
        'edit' => 'admin.user.edit'
    ]);

    // Route::get('/admin/user-management', [UserController::class, 'index'])->name('admin.user_management');
    // Route::post('/admin/user-management', [UserController::class, 'store'])->name('admin.user_management.store');
    Route::resource('/admin/users', UserController::class)->names([
        'index' => 'admin.users.index',
        'store' => 'admin.users.store',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);
    Route::get('/admin/profile', [ViewController::class, 'profile'])->name('admin.profile');

    Route::get('/admin/user-roles', [UserController::class, 'user_roles'])->name('admin.get_user_roles');
    require __DIR__.'/Admin/product.php';
    require __DIR__.'/Admin/event.php';
    require __DIR__.'/Admin/facility.php';

});