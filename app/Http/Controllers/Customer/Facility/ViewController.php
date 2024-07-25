<?php

namespace App\Http\Controllers\Customer\Facility;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ViewController extends Controller
{
    //
    public function checkout(Request $request)
    {
        return Inertia::render('Customer/Facility/Checkout', [
            'facility' => Facility::find($request->facility_id),
            'date' => $request->query('date'),
            'reservation_constants' => Reservation::getConstants()
        ]);
    }
}
