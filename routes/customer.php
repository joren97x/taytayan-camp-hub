<?php

use App\Http\Controllers\Customer\CustomerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {

    Route::put('/add-address', [CustomerController::class, 'add_address'])->name('add-address');

});

require __DIR__.'/Customer/product.php';
require __DIR__.'/Customer/event.php';
