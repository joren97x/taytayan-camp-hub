<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketHolder;
use App\Models\TicketOrder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketOrderController extends Controller
{
    public function dashboard(Event $event)
    {
        return Inertia::render('Cashier/EventDashboard', [
            'event' => $event,
            'ticket_orders' => TicketOrder::with('user', 'tickets')->where('event_id', $event->id)->get()
        ]);
    }

    public function check_in(Event $event)
    {
        $tickets = Ticket::whereHas('ticket_order', function ($query) use ($event) {
            $query->where('event_id', $event->id);
        })
        ->get();

        return Inertia::render('Cashier/EventCheckIn', [
            'event' => $event,
            'tickets' => $tickets
        ]);
    }

    public function verify($ticket_order_id) 
    {
        $ticket_order = TicketOrder::with([
            'event',
            'tickets'
        ])
        ->findOrFail($ticket_order_id);
    
        return Inertia::render('Cashier/VerifyTicketOrder', [
            'ticket_order' => $ticket_order
        ]);
    }
}
