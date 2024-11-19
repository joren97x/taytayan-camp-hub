<?php

namespace App\Http\Controllers\Admin\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ViewController extends Controller
{
    //
    public function reviews() {
        return Inertia::render('Admin/Event/Reviews');
    }

    public function dashboard() {

        $total_events = Event::count();

        $total_revenue = TicketOrder::where('status', TicketOrder::STATUS_COMPLETED)
        ->sum('amount');

        // $total_tickets_sold = Ticket::count()w;
        $total_tickets_sold = Event::sum('tickets_sold');
        // $tickets_sold_today = Ticket::where()
        $tickets_sold_today = Ticket::whereHas('ticket_order', function ($query) {
            $query->whereDate('created_at', today())
                  ->where('status', TicketOrder::STATUS_COMPLETED);
        })->count();

        $startDate = Carbon::now()->subMonth(); // One month before today
        $endDate = Carbon::now();              // Today
    
        // Get events within the date range
        $events = Event::whereBetween('date', [$startDate, $endDate])->get();
    
        // Get ticket counts grouped by event_id
        $ticketCounts = Ticket::selectRaw('ticket_orders.event_id, COUNT(tickets.id) as ticket_count')
        ->join('ticket_orders', 'tickets.ticket_order_id', '=', 'ticket_orders.id') // Join tickets with ticket_orders
        ->whereIn('ticket_orders.event_id', $events->pluck('id'))                  // Filter for events in range
        ->groupBy('ticket_orders.event_id')
        ->pluck('ticket_count', 'ticket_orders.event_id'); // Create a mapping of event_id to ticket_count

        // Format the data for the chart
        $event_labels = $events->pluck('date')->map(function ($date) {
            return Carbon::parse($date)->format('M d,Y'); // Format dates as needed
        })->toArray();
        
        $event_data = $events->map(function ($event) use ($ticketCounts) {
            return $ticketCounts[$event->id] ?? 0; // Default to 0 if no tickets
        })->toArray();

        return Inertia::render('Admin/Event/Dashboard', compact(
            'event_labels',
            'event_data',
            'total_events',
            'total_revenue',
            'total_tickets_sold',
            'tickets_sold_today',

        ));
    }
}
