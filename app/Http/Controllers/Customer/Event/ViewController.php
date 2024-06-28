<?php

namespace App\Http\Controllers\Customer\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TicketOrder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ViewController extends Controller
{
    //
    public function checkout(Request $request) {

        return Inertia::render('Customer/Event/Checkout', [
            'event' => Event::find($request->input('event_id')),
            'order_constants' => TicketOrder::getConstants()
        ]);
    }
}
