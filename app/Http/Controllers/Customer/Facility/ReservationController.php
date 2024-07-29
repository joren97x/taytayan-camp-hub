<?php

namespace App\Http\Controllers\Customer\Facility;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReservationController extends Controller
{
    //
    public function index()
    {
        return Inertia::render('Customer/Facility/Reservations', [
            'reservations' => Reservation::where('user_id', auth()->user()->id)->get()
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

}
