<?php

namespace App\Http\Controllers\Customer\Facility;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Luigel\Paymongo\Facades\Paymongo;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        // dd(User::where('role', 'cashier')->orderBy('updated_at', 'desc')->first());
        $active_bookings = Booking::with('facility')->whereIn('status', [
            Booking::STATUS_CHECKED_IN,
            Booking::STATUS_CONFIRMED,
            Booking::STATUS_PENDING,
            Booking::STATUS_CHECKED_OUT,
        ])
        ->where('user_id', auth()->id())
        ->get();

        $past_bookings = Booking::with('facility')->whereIn('status', [
            Booking::STATUS_CANCELLED,
            Booking::STATUS_COMPLETE,
        ])
        ->where('user_id', auth()->id())
        ->get();

        return Inertia::render('Customer/Facility/Bookings', [
            'active_bookings' => $active_bookings,
            'past_bookings' => $past_bookings
        ]);
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'facility_id' => 'required',
        //     'user_id' => 'required',
        //     'payment_method' => 'required',
        //     'guests' => 'required',
        //     'check_in' => 'required',
        //     'check_out' => 'required',
        //     'total' => 'required'
        // ]);

        // dd($request);

    }

    public function create()
    {
        //
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return Inertia::render('Customer/Facility/ShowBooking', [
            'booking' => Booking::with('facility')->find($id)
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

    public function complete(Request $request, string $id)
    {
        Booking::find($id)->update(['status' => Booking::STATUS_COMPLETE]);
        return back();
    }

    public function cancel(Request $request, string $id)
    {
        $booking = Booking::find($id);

        if($booking->payment_method != 'walk_in') {
            $payment = Paymongo::payment()->find($booking->payment_id);
            Paymongo::refund()->create([
                'amount' => $payment->amount,
                'reason' => \Luigel\Paymongo\Models\Refund::REASON_REQUESTED_BY_CUSTOMER, // `duplicate`, `fraudulent`, required by customer
                'payment_id' => $booking->payment_id
            ]);
        }

        $booking->update([
            'status' => Booking::STATUS_CANCELLED
        ]);

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
