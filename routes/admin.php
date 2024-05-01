<?php

use App\Http\Controllers\Admin\ViewController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->group(function() {
    Route::get('/admin/dashboard', [ViewController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/user-management', [ViewController::class, 'user_management'])->name('admin.user_management');
    require __DIR__.'/Admin/product.php';

});