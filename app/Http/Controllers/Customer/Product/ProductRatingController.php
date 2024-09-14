<?php

namespace App\Http\Controllers\Customer\Product;
use App\Http\Controllers\Controller;
use App\Models\ProductRating;
use Illuminate\Http\Request;

class ProductRatingController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'rating',
        ]);

        ProductRating::create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'review' => $request->review
        ]);

        return back();

    }
}
