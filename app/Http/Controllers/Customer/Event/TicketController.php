<?php

namespace App\Http\Controllers\Customer\Event;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    //
    public function index(Request $request) 
    {

        // $active_ticket_orders = TicketOrder::with([
        //     'event', 
        //     'ticket_order_items', 
        //     'ticket_order_items.ticket.ticket_holder',
        // ])
        // ->whereIn('status', [
        //     TicketOrder::STATUS_CONFIRMED,
        //     TicketOrder::STATUS_PENDING,
        //     TicketOrder::STATUS_SCANNED,
        //     TicketOrder::S,
        // ])
        // ->where('user_id', auth()->user()->id)
        // ->get();

        // $past_ticket_orders = T
        // ->where('user_id', auth()->user()->id)
        // ->get();

        $today = Carbon::today();
        $active_ticket_orders = TicketOrder::with([
            'event', 
            'ticket_order_items', 
            'ticket_order_items.ticket.ticket_holder',
        ])
        ->whereHas('event', function($query) use ($today) {
            $query->where('date', '>=', $today);
        })
        ->where('user_id', auth()->id())
        ->get();

        $past_ticket_orders = TicketOrder::with([
            'event', 
            'ticket_order_items', 
            'ticket_order_items.ticket.ticket_holder',
        ])
        ->whereHas('event', function($query) use ($today) {
            $query->where('date', '<', $today);
        })
        ->where('user_id', auth()->id())
        ->get();
        
        
        return Inertia::render('Customer/Event/Tickets', [
            'active_ticket_orders' =>  $active_ticket_orders,
            'past_ticket_orders' =>  $past_ticket_orders,
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
