<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Luigel\Paymongo\Facades\Paymongo;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Customer\ViewController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\MilkteaViewController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if(Auth::check()) {
        return redirect('/home');
    }
    return Inertia::render('Homepage', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('homepage');

Route::get('/home', [ViewController::class, 'home'])->name('home');

Route::get('/events', function() {
    return Inertia::render('Customer/Event/Index');
})->name('events');

Route::middleware(['auth', 'customer'])->group(function () {
    Route::get('/home', [ViewController::class, 'home'])->name('home');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/admin.php';
require __DIR__.'/customer.php';
require __DIR__.'/driver.php';
require __DIR__.'/cashier.php';
require __DIR__.'/auth.php';
