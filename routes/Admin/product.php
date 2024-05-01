<?php

use App\Http\Controllers\Admin\Product\ViewController;
use App\Http\Controllers\Admin\Product\CategoryController;
use App\Http\Controllers\Admin\Product\ModifierGroupController;
use App\Http\Controllers\Admin\Product\ModifierItemController;
use App\Http\Controllers\Admin\Product\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/orders', [ViewController::class, 'orders'])->name('admin.orders');
Route::get('/admin/product/reviews', [ViewController::class, 'reviews'])->name('admin.product.reviews');
// Route::get('/admin/modifier-groups', [ViewController::class, 'modifier_groups'])->name('admin.modifier_groups');
// Route::get('/admin/new-modifier-group', [ViewController::class, 'new_modifier_group'])->name('admin.new_modifier_group');
// Route::get('/admin/new-product', [ViewController::class, 'new_product'])->name('admin.new_product');
Route::resource('/admin/products', ProductController::class)->names([
    'index' => 'admin.product.index',
    'create' => 'admin.product.create',
    'store' => 'admin.product.store'
]);

Route::resource('/admin/modifier-groups', ModifierGroupController::class)->names([
    'index' => 'admin.modifier_group.index',
    'create' => 'admin.modifier_group.create',
]);

Route::resource('/admin/categories', CategoryController::class)->names([
    'index' => 'admin.category.index',
    'create' => 'admin.category.create',
    'store' => 'admin.category.store'
]);

Route::resource('/admin/modifier-items', ModifierItemController::class)->names([
    'store' => 'admin.modifier_items.store'
]);