<?php

namespace App\Http\Controllers\Admin\Facility;

use App\Http\Controllers\Controller;
use App\Models\FacilityRating;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FacilityRatingController extends Controller
{
    //
    public function index()
    {
        //
        return Inertia::render('Admin/Facility/Ratings', [
            'ratings' => FacilityRating::with('user')->get()
        ]);
    }
}
