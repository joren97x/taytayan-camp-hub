<?php

namespace App\Http\Controllers\Cashier;

use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Inertia::render('Cashier/Bookings', [
            'bookings' => Booking::with(['facility', 'user'])->get(),
            'booking_statuses' => Booking::getStatuses()
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

    public function check_in(Request $request, string $id)
    {
        //
        Booking::find($id)->update(['status' => Booking::STATUS_CHECKED_IN]);
        return back();
    }

    public function check_out(Request $request, string $id)
    {
        //
        $booking = Booking::find($id);
        $booking->update(['status' => Booking::STATUS_CHECKED_OUT]);
        
        $notification = Notification::create([
            'user_id' => $booking->user_id,
            'title' => 'Booking Complete',
            'description' => 'Your booking has been completed successfully!',
            'link' => route('customer.bookings.show', $booking->id)
        ]);
        // dd($notification);
        event(new Notify($notification));

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
