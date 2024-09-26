<?php

namespace App\Http\Controllers\Customer\Facility;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Facility;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ViewController extends Controller
{
    //
    public function checkout(Request $request)
    {

        $facility = Facility::find($request->facility_id);

        $reserved_dates = Booking::where('facility_id', $facility->id)
        ->where('status', '!=', Booking::STATUS_CANCELLED)  
        ->select('check_in', 'check_out')
        ->get();

        return Inertia::render('Customer/Facility/Checkout', [
            'facility' => $facility,
            'date' => $request->query('date'),
            'reservation_constants' => Booking::getConstants(),
            'reserved_dates' => $reserved_dates
        ]);
    }
}
