<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    //
    public function update(Ticket $ticket)
    {
        $ticket->update([
            'status' => Ticket::STATUS_USED
        ]);
        return back();
    }

    public function undo(Ticket $ticket)
    {
        $ticket->update([
            'status' => Ticket::STATUS_SOLD
        ]);
        return back();
    }

    public function index()
    {
        return Inertia::render('Cashier/Tickets', [
            'events' => Event::with('ticket_orders')->get()
        ]);
    }
}
