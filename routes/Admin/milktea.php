<?php

use App\Http\Controllers\Admin\AdminMilkteaViewController;
use App\Http\Controllers\Admin\MilkteaController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/orders', [AdminMilkteaViewController::class, 'orders'])->name('admin.orders');
Route::get('/admin/milkteas/reviews', [AdminMilkteaViewController::class, 'reviews'])->name('admin.milktea.reviews');
Route::get('/admin/group-modifiers', [AdminMilkteaViewController::class, 'group_modifiers'])->name('admin.group_modifiers');
Route::resource('/admin/milktea', MilkteaController::class)->names([
    'index' => 'admin.milktea.index'
]);