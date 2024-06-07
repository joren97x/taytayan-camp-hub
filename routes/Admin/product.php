<?php

use App\Http\Controllers\Admin\Product\ViewController;
use App\Http\Controllers\Admin\Product\CategoryController;
use App\Http\Controllers\Admin\Product\ModifierGroupController;
use App\Http\Controllers\Admin\Product\ModifierItemController;
use App\Http\Controllers\Admin\Product\OrderController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/user-roles', [UserController::class, 'user_roles'])->name('admin.get_user_roles');
Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders');
Route::patch('/admin/update-order-status/{order}', [OrderController::class, 'update_status'])->name('admin.order.update_status');
Route::get('/admin/product/reviews', [ViewController::class, 'reviews'])->name('admin.product.reviews');
// Route::get('/admin/modifier-groups', [ViewController::class, 'modifier_groups'])->name('admin.modifier_groups');
// Route::get('/admin/new-modifier-group', [ViewController::class, 'new_modifier_group'])->name('admin.new_modifier_group');
// Route::get('/admin/new-product', [ViewController::class, 'new_product'])->name('admin.new_product');
Route::resource('/admin/products', ProductController::class)->names([
    'index' => 'admin.product.index',
    'create' => 'admin.product.create',
    'store' => 'admin.product.store',
    'edit' => 'admin.product.edit',
    'update' => 'admin.product.update'
]);

Route::put('/admin/products/{product}/update-modifier-group', [ProductController::class, 'update_modifier_group'])->name('admin.product.update_modifier_group');
Route::post('/admin/products/{product}/update-photo', [ProductController::class, 'update_photo'])->name('admin.product.update_photo');
Route::resource('/admin/modifier-groups', ModifierGroupController::class)->names([
    'index' => 'admin.modifier_group.index',
    'create' => 'admin.modifier_group.create',
    'store' => 'admin.modifier_group.store'
]);

Route::resource('/admin/categories', CategoryController::class)->names([
    'index' => 'admin.category.index',
    'create' => 'admin.category.create',
    'store' => 'admin.category.store'
]);

Route::resource('/admin/modifier-items', ModifierItemController::class)->names([
    'store' => 'admin.modifier_item.store'
]);