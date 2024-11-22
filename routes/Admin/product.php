<?php

use App\Http\Controllers\Admin\Product\ViewController;
use App\Http\Controllers\Admin\Product\CategoryController;
use App\Http\Controllers\Admin\Product\ModifierGroupController;
use App\Http\Controllers\Admin\Product\ModifierItemController;
use App\Http\Controllers\Admin\Product\OrderController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Product\ProductRatingController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// Route::patch('/admin/orders/update-order-status/{id}', function() {
//     dd('hhh');
// })->name('admin.orders.update_status');
Route::get('/dashboard/products', [ViewController::class, 'dashboard'])->name('admin.products.dashboard');
Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
// Route::patch('/update-order-status/{id}', [OrderController::class, 'update_status'])->name('admin.order.update_status');
Route::resource('/product/ratings', ProductRatingController::class)->names([
    'index' => 'admin.product_ratings.index',
    'create' => 'admin.product_ratings.create',
    'show' => 'admin.product_ratings.show',
    'store' => 'admin.product_ratings.store',
    'update' => 'admin.product_ratings.update',
    'destroy' => 'admin.product_ratings.destroy',
    'edit' => 'admin.product_ratings.edit',
]);
Route::resource('/products', ProductController::class)->names([
    'index' => 'admin.products.index',
    'create' => 'admin.products.create',
    'store' => 'admin.products.store',
    'edit' => 'admin.products.edit',
    'update' => 'admin.products.update',
    'destroy' => 'admin.products.destroy',
    'show' => 'admin.products.show',
]);

Route::put('/products/{product}/update-modifier-group', [ProductController::class, 'update_modifier_group'])->name('admin.products.update_modifier_group');
Route::delete('/products/{product}/clear-modifier-group', [ProductController::class, 'clear_modifier_group'])->name('admin.products.clear_modifier_group');
Route::post('/products/{product}/update-photo', [ProductController::class, 'update_photo'])->name('admin.products.update_photo');

Route::resource('/modifier-groups', ModifierGroupController::class)->names([
    'index' => 'admin.modifier_groups.index',
    'create' => 'admin.modifier_groups.create',
    'store' => 'admin.modifier_groups.store',
    'edit' => 'admin.modifier_groups.edit',
    'update' => 'admin.modifier_groups.update',
    'destroy' => 'admin.modifier_groups.destroy',
]);

Route::resource('/categories', CategoryController::class)->names([
    'index' => 'admin.categories.index',
    'create' => 'admin.categories.create',
    'store' => 'admin.categories.store',
    'destroy' => 'admin.categories.destroy',
    'update' => 'admin.categories.update'
]);

Route::resource('/modifier-items', ModifierItemController::class)->names([
    'store' => 'admin.modifier_items.store',
    'update' => 'admin.modifier_items.update',
    'destroy' => 'admin.modifier_items.destroy',
]);