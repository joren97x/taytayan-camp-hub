<?php

namespace App\Http\Controllers\Admin\Facility;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReservationController extends Controller
{
    //
    public function index()
    {
        return Inertia::render('Admin/Facility/Reservations', [
            'reservations' => Reservation::get()
        ]);
    }
}
