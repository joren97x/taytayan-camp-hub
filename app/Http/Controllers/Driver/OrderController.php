<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    //
    public function index() {

        $orders = Order::whereIn('status', [
            Order::STATUS_READY_FOR_DELIVERY,
            Order::STATUS_DELIVERING
        ])->get();

        return Inertia::render('Driver/Orders', ['orders' => $orders]);
    }
}
