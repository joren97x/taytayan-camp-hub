<?php

namespace App\Http\Controllers\Customer\Facility;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ViewController extends Controller
{
    //
    public function checkout(Request $request)
    {

        $facility = Facility::withCount(['facility_ratings as average_rating' => function ($query) {
            $query->select(DB::raw('coalesce(avg(rating), 0)'));
        }])->
        find($request->facility_id);

        $reserved_dates = Booking::where('facility_id', $facility->id)
        ->where('status', '!=', Booking::STATUS_CANCELLED)  
        ->select('check_in', 'check_out')
        ->get();

        return Inertia::render('Customer/Facility/Checkout', [
            'facility' => $facility,
            'date' => $request->query('date'),
            'guests' => $request->query('guests'),
            'reserved_dates' => $reserved_dates
        ]);
    }
}
