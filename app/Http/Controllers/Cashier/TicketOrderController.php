<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TicketOrder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketOrderController extends Controller
{
    //
    public function index()
    {
        return Inertia::render('Cashier/Tickets', [
            'events' => Event::with('ticket_orders')->get()
        ]);
    }

    public function dashboard(Event $event)
    {



        return Inertia::render('Cashier/EventDashboard', [
            'event' => $event,
            'ticket_orders' => TicketOrder::with('user', 'ticket_order_items')->where('event_id', $event->id)->get()
        ]);
    }

    public function verify($ticket_order_id) 
    {
        $ticket_order = TicketOrder::with([
            'event', 
            'ticket_order_items', 
            'ticket_order_items.ticket.ticket_holder',
        ])
        ->find($ticket_order_id);
    
        return Inertia::render('Cashier/VerifyTicketOrder', [
            'ticket_order' => $ticket_order
        ]);
    }
}
