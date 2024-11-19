<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketOrder;
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

        $allTicketsUsed = Ticket::where('ticket_order_id', $ticket->ticket_order_id)
        ->where('status', '!=', Ticket::STATUS_USED)
        ->doesntExist();

        // dd($allTicketsUsed);

        if ($allTicketsUsed) {
            $ticket->ticket_order->update([
                'status' => TicketOrder::STATUS_COMPLETED
            ]);
        }

        return back();
    }

    public function undo(string $id)
    {

        $ticket = Ticket::with('ticket_order')->find($id);

        $ticket->update([
            'status' => Ticket::STATUS_PENDING
        ]);
    
        // Check the payment method and set the ticket order status accordingly
        $newStatus = $ticket->ticket_order->payment_method === 'walk in' 
            ? TicketOrder::STATUS_PENDING 
            : TicketOrder::STATUS_CONFIRMED;
    
        // Update the ticket order status based on the payment method
        $ticket->ticket_order->update([
            'status' => $newStatus
        ]);

        return back();
    }

    public function index()
    {
        return Inertia::render('Cashier/Tickets', [
            'events' => Event::with('ticket_orders')->get()
        ]);
    }

    public function store(Request $request)
    {
        // dd($request);

        $request->validate([ 'name' => 'required' ]);
        $event = Event::find($request->event_id);

        $ticket_order = TicketOrder::create([
            'event_id' => $request->event_id,
            'payment_method' => 'walk_in',
            'status' => TicketOrder::STATUS_COMPLETED,
            'amount' => $event->price
        ]);

        Ticket::create([
            'ticket_order_id' => $ticket_order->id,
            'name' => $request->name,
            'status' => Ticket::STATUS_USED
        ]);

        $event->increment('tickets_sold');
    }
}
