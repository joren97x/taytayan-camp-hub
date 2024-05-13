<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ViewController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->group(function() {
    Route::get('/admin/dashboard', [ViewController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('/admin/user-management', UserController::class)->names([
        'index' => 'admin.user.index',
        'show' => 'admin.user.show',
        'edit' => 'admin.user.edit'
    ]);
    require __DIR__.'/Admin/product.php';

});