<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Facility;
use App\Models\FacilityRating;
use App\Models\Product;
use App\Models\ProductRating;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        
        return Inertia::render('Test', [
            'products' => [],
            'events' => [],
            'facilities' => [],
            'ratings' => []
        ]);
        // return Inertia::render('Test', ['products' => Product::all()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render('Customer/Test/Create', ['products' => Product::all()]);
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
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        return Inertia::render('Customer/Test/Edit', ['products' => Product::all()]);
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
