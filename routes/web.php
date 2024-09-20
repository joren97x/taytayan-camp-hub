<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConversationController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Luigel\Paymongo\Facades\Paymongo;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Customer\ViewController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\MessageController;
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
    Route::patch('/profile/update-profile-pic', [ProfileController::class, 'update_profile_pic'])->name('profile.update_profile_pic');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/conversations/get-users-with-convo', [ConversationController::class, 'get_users_with_convo'])->name('conversations.get_users_with_convo');
Route::get('/conversations/get-users', [ConversationController::class, 'get_users'])->name('conversations.get_users');

require __DIR__.'/admin.php';
require __DIR__.'/customer.php';
require __DIR__.'/driver.php';
require __DIR__.'/cashier.php';
require __DIR__.'/auth.php';

Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store'])->name('messages.store');
// Route::get('/conversations/{conversation}/messages', [MessageController::class, 'get_messages']);
// Route::get('/conversations/{id}', [ConversationController::class, 'show'])->name('conversations.show');
// Route::post('/conversations/{user_id}', [ConversationController::class, 'store'])->name('conversations.store');
// Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
Route::resource('conversations', ConversationController::class)->names([
    'show' => 'conversations.show',
    'store' => 'conversations.store',
    'index' => 'conversations.index'
]);
// Route::get('/conversations/c/{id}', [ConversationController::class, 'show_convo'])->name('show_convo');
// Route::get('/conversations/{id}', [ConversationController::class, 'get_users_with_convo'])->name('conversations.get_users_with_convo');

Route::get('/test', function() {
    return Inertia::render('Test');
});