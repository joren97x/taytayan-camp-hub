<?php

namespace App\Http\Controllers\Admin\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
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

        $cover_photo = $request->cover_photo[0]->getClientOriginalName();
        $request->cover_photo[0]->move(public_path('/images'), $cover_photo);

        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'cover_photo' => $cover_photo,
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
                'ticket_code' => Str::random(15)
            ]);
        }

        return back();

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return Inertia::render('Admin/Event/ShowEvent', [
            'event' => Event::find($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        return Inertia::render('Admin/Event/EditEvent');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function event_dashboard(string $id) {
        return Inertia::render('Admin/Event/EventDashboard');
    }

}
