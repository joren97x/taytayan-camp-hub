<?php

namespace App\Http\Controllers\Admin\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketOrder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Inertia::render('Admin/Event/Events', [
            'events' => Event::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render('Admin/Event/CreateEvent');
    }

    public function dashboard(Event $event)
    {
        return Inertia::render('Admin/Event/Dashboard', [
            'event' => $event,
            'ticket_orders' => TicketOrder::with('user', 'ticket_order_items')->where('event_id', $event->id)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'cover_photo' => 'required',
            'date' => ['required', 'date'],
            'capacity' => 'required',
            'start_time' => 'required',
            'location' => 'required',
            'admission_fee' => 'required',
            'min_ticket' => 'required',
            'max_ticket' => 'required'
        ]);

        $path = $request->file('cover_photo')->store('events', 'public');

        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'cover_photo' => $path,
            'date' => $request->date,
            'capacity' => $request->capacity,
            'start_time' => $request->start_time,
            'location' => $request->location,
            'admission_fee' => $request->admission_fee,
            'min_ticket' => $request->min_ticket,
            'max_ticket' => $request->max_ticket
        ]); 

        for($i = 0; $i < $request->capacity; $i++) {
            Ticket::create([
                'event_id' => $event->id,
                // 'ticket_code' => Str::random(15)
            ]);
        }

        return redirect(route('admin.events.index'));

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return Inertia::render('Admin/Event/ShowEvent', [
            'event' => Event::with([
                'ticket_orders',
                'ticket_orders.user',
                'ticket_orders.ticket_order_items',
                'ticket_orders.ticket_order_items.ticket.ticket_holder',
            ])->find($id)
            // 'ticket_orders' =>  TicketOrder::with([
            //     'event', 
            //     'ticket_order_items', 
            //     'ticket_order_items.ticket.ticket_holder',
            // ])
            // ->where('user_id', auth()->user()->id)
            // ->get()
            
        ]);
    }

    public function change_status(Request $request, Event $event)
    {
        $event->update(['status' => $request->status]);
        return back();
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        return Inertia::render('Admin/Event/EditEvent', [
            'event' => Event::find($id),
            'event_statuses' => Event::getStatus()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'date' => ['required', 'date'],
            'capacity' => 'required',
            'start_time' => 'required',
            'location' => 'required',
            'admission_fee' => 'required',
            'min_ticket' => 'required',
            'max_ticket' => 'required'
        ]);

        $event = Event::find($id);

        $event->title = $request->title;
        $event->description = $request->description;
        $event->date = $request->date;
        $event->capacity = $request->capacity;
        $event->start_time = $request->start_time;
        $event->location = $request->location;
        $event->admission_fee = $request->admission_fee;
        $event->min_ticket = $request->min_ticket;
        $event->max_ticket = $request->max_ticket;

        $event->update();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        Event::destroy($id);
        return back();
    }

    public function event_dashboard(string $id) {
        return Inertia::render('Admin/Event/EventDashboard');
    }

    public function update_cover_photo(Request $request, string $id)
    {
        // dd($request);
        $request->validate([
            'cover_photo' => 'required'
        ]);
        // dd($request->photo);
        // $photo = $request->photo[0]->getClientOriginalName();
        // $request->photo[0]->move(public_path('/images'), $photo);
        $cover_photo = $request->cover_photo->store('events', 'public');

        $event = Event::find($id);

        $event->cover_photo = $cover_photo;
        $event->save();

        return back();
    }

}
