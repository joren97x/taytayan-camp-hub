<?php

namespace App\Http\Controllers\Customer\Facility;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        return Inertia::render('Customer/Facility/Bookings', [
            'bookings' => Booking::where('user_id', auth()->id())->with('facility')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'facility_id' => 'required',
            'user_id' => 'required',
            'payment_method' => 'required',
            'guests' => 'required',
            'check_in' => 'required',
            'check_out' => 'required',
            'total' => 'required'
        ]);

        dd($request);

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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
