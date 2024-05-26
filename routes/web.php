<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Luigel\Paymongo\Facades\Paymongo;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Customer\ViewController;
use App\Http\Controllers\Customer\MilkteaViewController;

Route::get('/', function () {
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

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [ViewController::class, 'home'])->name('home');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('pay', function() {

    $checkout = Paymongo::checkout()->create([
        'cancel_url' => route('customer.cart.index'),
        'billing' => [
            'name' => 'Juan Doe',
            'email' => 'juan@doe.com',
            'phone' => '+639123456789',
        ],
        'description' => 'My checkout session description',
        'line_items' => [
            [
                'amount' => 10000,
                'currency' => 'PHP',
                'description' => 'Something of a product.',
                'images' => [
                    'https://images.unsplash.com/photo-1613243555988-441166d4d6fd?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80'
                ],
                'name' => 'A payment card',
                'quantity' => 1
            ]
        ],
        'payment_method_types' => [
            'atome',
            'billease',
            'card',
            'dob',
            'dob_ubp',
            'gcash',
            'grab_pay', 
            'paymaya'
        ],
        'success_url' => route('customer.cart.index'),
        'statement_descriptor' => 'Laravel Paymongo Library',
        'metadata' => [
            'Key' => 'Value'
        ]
    ]);

    return redirect($checkout->checkout_url);
    // dd($checkout->checkout_url);

});



require __DIR__.'/admin.php';
require __DIR__.'/customer.php';
require __DIR__.'/auth.php';
