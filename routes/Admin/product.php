<?php

use App\Http\Controllers\Admin\Product\ViewController;
use App\Http\Controllers\Admin\Product\CategoryController;
use App\Http\Controllers\Admin\Product\ModifierGroupController;
use App\Http\Controllers\Admin\Product\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/orders', [ViewController::class, 'orders'])->name('admin.orders');
Route::get('/admin/product/reviews', [ViewController::class, 'reviews'])->name('admin.product.reviews');
// Route::get('/admin/modifier-groups', [ViewController::class, 'modifier_groups'])->name('admin.modifier_groups');
// Route::get('/admin/new-modifier-group', [ViewController::class, 'new_modifier_group'])->name('admin.new_modifier_group');
// Route::get('/admin/new-product', [ViewController::class, 'new_product'])->name('admin.new_product');
Route::resource('/admin/product', ProductController::class)->names([
    'index' => 'admin.product.index',
    'create' => 'admin.product.create'
]);

Route::resource('/admin/modifier-group', ModifierGroupController::class)->names([
    'index' => 'admin.modifier_group.index',
    'create' => 'admin.modifier_group.create',
]);

Route::resource('/admin/categories', CategoryController::class)->names([
    'index' => 'admin.category.index',
    'create' => 'admin.category.create'
]);