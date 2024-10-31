<?php

namespace App\Http\Controllers\Customer\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $currentDateTime = now();
        // dd($currentDateTime->toDateString());
        return Inertia::render('Customer/Event/Index', [
            'upcoming_events' => Event::where('date', '>', $currentDateTime->toDateString())
                ->orWhere(function ($query) use ($currentDateTime) {
                    $query->where('date', '=', $currentDateTime->toDateString())
                        ->where('start_time', '>', $currentDateTime->toTimeString());
                })
                ->where('status', Event::STATUS_ON_SALE)
                ->get(),

            'past_events' => Event::where('date', '<', $currentDateTime->toDateString())
                ->orWhere(function ($query) use ($currentDateTime) {
                    $query->where('date', '=', $currentDateTime->toDateString())
                        ->where('start_time', '<', $currentDateTime->toTimeString());
                })
                ->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return Inertia::render('Customer/Event/Show', [
            'event' => Event::find($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
        // return back();
    }
}
