<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Facility;
use App\Models\FacilityRating;
use App\Models\Product;
use App\Models\ProductRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class HomeController extends Controller
{
    //
    public function home() {
        $products = Product::with('modifier_groups.modifier_items')
            ->where('available', true)
            ->get();

        $currentDateTime = now();
        $events = Event::where('date', '>', $currentDateTime->toDateString())
        ->orWhere(function ($query) use ($currentDateTime) {
            $query->where('date', '=', $currentDateTime->toDateString())
                ->where('start_time', '>', $currentDateTime->toTimeString());
        })
        ->where('status', Event::STATUS_ON_SALE)
        ->get();

        $facilities = Facility::where('available', true)
            ->withCount(['facility_ratings as average_rating' => function ($query) {
                $query->select(DB::raw('coalesce(avg(rating), 0)'));
            }])
            ->get();

        $product_ratings = ProductRating::with('user')->latest()->get();
        $facility_ratings = FacilityRating::with('user')->latest()->get();

        $ratings = collect($product_ratings)->merge($facility_ratings);
        
        return Inertia::render('Customer/Index', [
            'products' => [],
            'events' => [],
            'facilities' => [],
            'ratings' => []
        ]);
    }
}
