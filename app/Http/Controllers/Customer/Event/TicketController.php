<?php

namespace App\Http\Controllers\Customer\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    //
    public function index() {
        return Inertia::render('Customer/Event/Tickets', [
            'tickets' => 'hello'
        ]);
    }
}
