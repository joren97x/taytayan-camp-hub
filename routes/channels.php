<?php

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('orders.{id}', function(User $user, int $order_id) {
    return $user->id == Order::find($order_id)->user_id;
});

Broadcast::channel('orders', function(User $user) {
    // ang maka listen ra ani kay ang cashier ug driver
    return true;
});

Broadcast::channel('conversation.{id}', function() {
    return true;
});
