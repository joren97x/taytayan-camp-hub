<?php

use App\Http\Controllers\Admin\AdminMilkteaViewController;
use App\Http\Controllers\Admin\MilkteaController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/milkteas/orders', [AdminMilkteaViewController::class, 'orders'])->name('admin.orders');
Route::get('/admin/milkteas/reviews', [AdminMilkteaViewController::class, 'reviews'])->name('admin.milktea.reviews');
Route::get('/admin/milkteas/modifier-groups', [AdminMilkteaViewController::class, 'modifier_groups'])->name('admin.modifier_groups');
Route::get('/admin/milkteas/new-modifier-group', [AdminMilkteaViewController::class, 'new_modifier_group'])->name('admin.new_modifier_group');
Route::get('/admin/milkteas/new-milktea', [AdminMilkteaViewController::class, 'new_milktea'])->name('admin.new_milktea');
Route::resource('/admin/milkteas', MilkteaController::class)->names([
    'index' => 'admin.milktea.index',
    'create' => 'admin.milktea.create'
]);