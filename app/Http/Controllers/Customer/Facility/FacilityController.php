<?php

namespace App\Http\Controllers\Customer\Facility;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Facility;
use App\Models\FacilityRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Inertia::render('Customer/Facility/Index', [
            'facilities' => Facility::where('available', true)
            ->withCount(['facility_ratings as average_rating' => function ($query) {
                $query->select(DB::raw('coalesce(avg(rating), 0)'));
            }])
            ->get()
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
        $facility = Facility::with('facility_ratings.user')
        ->withCount(['facility_ratings as average_rating' => function ($query) {
            $query->select(DB::raw('coalesce(avg(rating), 0)'));
        }])
        ->find($id);

        $reserved_dates = Booking::where('facility_id', $facility->id)
        ->where('status', '!=', Booking::STATUS_CANCELLED)  
        ->select('check_in', 'check_out')
        ->get();

        return Inertia::render('Customer/Facility/Show', [
            'facility' => $facility,
            'reserved_dates' => $reserved_dates,
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
    }
}
