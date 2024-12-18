<?php

namespace App\Http\Controllers\Customer\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Luigel\Paymongo\Facades\Paymongo;

class TicketController extends Controller
{
    //
    public function index(Request $request) 
    {

        $ticket_orders = TicketOrder::with([
            'event', 
            'tickets',
        ])
        ->where('user_id', auth()->id())
        ->latest()
        ->get();
        
        return Inertia::render('Customer/Event/Tickets', [
            'ticket_orders' =>  $ticket_orders
        ]);
        
    }

    public function show(string $id)
    {
        $ticket_order = TicketOrder::with([
            'event',
            'tickets'
        ])->findOrFail($id);

        if($ticket_order->user_id != auth()->id()) {
            abort(404);
        }
        
        return Inertia::render('Customer/Event/ShowTicket', [
            'ticket_order' => $ticket_order
        ]);
    }

    public function cancel(string $id)
    {
        $ticket_order = TicketOrder::with('tickets')->find($id);
        $event = Event::find($ticket_order->event_id);
        
        foreach($ticket_order->tickets as $ticket) {
            $event->decrement('tickets_sold');
            $ticket->update([ 'status' => Ticket::STATUS_CANCELLED ]);
        }

        if($ticket_order->payment_method != 'walk_in') {
            $payment = Paymongo::payment()->find($ticket_order->payment_id);
            Paymongo::refund()->create([
                'amount' => $payment->amount,
                'reason' => \Luigel\Paymongo\Models\Refund::REASON_REQUESTED_BY_CUSTOMER, // `duplicate`, `fraudulent`, required by customer
                'payment_id' => $ticket_order->payment_id
            ]);
        }

        $ticket_order->update(['status' => TicketOrder::STATUS_CANCELLED ]);

        return back();
        // dd($ticket_order);
    }

}
