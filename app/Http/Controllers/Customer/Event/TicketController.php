<?php

namespace App\Http\Controllers\Customer\Event;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketOrder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    //
    public function index(Request $request) 
    {

        return Inertia::render('Customer/Event/Tickets', [
            'ticket_orders' =>  TicketOrder::with([
                'event', 
                'ticket_order_items', 
                'ticket_order_items.ticket.ticket_holder',
            ])
            ->where('user_id', auth()->user()->id)
            ->get()
        ]);
        
    }

    public function show(string $id)
    {
        $ticket_order = TicketOrder::with([
            'event', 
            'ticket_order_items', 
            'ticket_order_items.ticket.ticket_holder',
        ])->find($id);
        
        return Inertia::render('Customer/Event/ShowTicket', [
            'ticket_order' => $ticket_order
        ]);
    }

}
