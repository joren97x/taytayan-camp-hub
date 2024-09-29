<?php

namespace App\Http\Controllers\Customer\Facility;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilityRating;
use Illuminate\Http\Request;

class FacilityRatingController extends Controller
{
    //
    public function store(Request $request, string $id)
    {
        $facility = Facility::find($id);
        
        $request->validate([
            'rating'
        ]);

        FacilityRating::create([
            'user_id' => auth()->id(),
            'facility_id' => $facility->id,
            'rating' => $request->rating,
            'review' => $request->review
        ]);

        return back();

    }
}
