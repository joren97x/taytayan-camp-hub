<?php

namespace App\Http\Controllers\Admin\Facility;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Inertia::render('Admin/Facility/Facilities', [
            'facilities' => Facility::get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render('Admin/Facility/CreateFacility');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'images' => 'required',
            'guests' => 'required',
            'amenities' => 'required',
            'price' => 'required'
        ]);

        $image_paths = [];
        for($i = 0; $i < count($request->images); $i++) {
            $path = $request->file('images')[$i]->store('facilities', 'public');
            array_push($image_paths, $path);
        }

        Facility::create([
            'name' => $request->name,
            'description' => $request->description,
            'guests' => $request->guests,
            'images' => json_encode($image_paths),
            'amenities' => json_encode($request->amenities),
            'price' => $request->price
        ]);

        return back();

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return Inertia::render('Admin/Facility/ShowFacility', [
            'facility' => Facility::find($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        return Inertia::render('Admin/Facility/EditFacility', [
            'facility' => Facility::find($id)
        ]);
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
