<?php

use App\Http\Controllers\Admin\AdminViewController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->group(function() {
    Route::get('/admin/dashboard', [AdminViewController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/user-management', [AdminViewController::class, 'user_management'])->name('admin.user_management');
    require __DIR__.'/Admin/milktea.php';

});