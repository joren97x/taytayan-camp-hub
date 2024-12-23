<?php

use App\Http\Controllers\ConversationController;
use App\Http\Controllers\Customer\Event\EventController;
use App\Http\Controllers\Customer\Facility\FacilityController;
use App\Http\Controllers\Customer\Product\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Customer\ViewController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TestController;
use Inertia\Inertia;

Route::get('/', [ViewController::class, 'home'])->name('home');

Route::resource('events', EventController::class)->names([
    'index' => 'customer.events.index',
    'show' => 'customer.events.show',
]);
Route::resource('facilities', FacilityController::class)->names([
    'index' => 'customer.facilities.index',
    'show' => 'customer.facilities.show',
]);
Route::resource('products', ProductController::class)->names([
    'index' => 'customer.products.index',
    'show' => 'customer.products.show'
]);
Route::get('/search/{query?}', [ViewController::class, 'search'])->name('search');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/profile/update-profile-pic', [ProfileController::class, 'update_profile_pic'])->name('profile.update_profile_pic');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //idk about these two
    Route::get('/conversations/get-users-with-convo', [ConversationController::class, 'get_users_with_convo'])->name('conversations.get_users_with_convo');
    Route::get('/conversations/get-users', [ConversationController::class, 'get_users'])->name('conversations.get_users');

    Route::post('/messages/{conversation}', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/chat-user/{user}', [ConversationController::class, 'chat_user'])->name('conversations.chat_user');
    Route::get('/chat-cashier', [ConversationController::class, 'chat_cashier'])->name('conversations.chat_cashier');
    Route::get('/inbox/get-conversations', [ConversationController::class, 'get_conversations'])->name('conversations.get_conversations');
    Route::get('/inbox/get-conversations/{id}', [ConversationController::class, 'get_conversation'])->name('conversations.get_conversation');
    Route::resource('inbox', ConversationController::class)->names([
        'show' => 'conversations.show',
        'store' => 'conversations.store',
        'index' => 'conversations.index'
    ]);

});


require __DIR__.'/admin.php';
require __DIR__.'/customer.php';
require __DIR__.'/driver.php';
require __DIR__.'/cashier.php';
require __DIR__.'/auth.php';

