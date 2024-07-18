<?php

namespace App\Http\Controllers\Customer\Facility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReservationController extends Controller
{
    //
    public function index()
    {
        return Inertia::render('Customer/Facility/Reservations');
    }
}
