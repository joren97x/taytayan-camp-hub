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
        // dd($request);
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

        return redirect(route('admin.facilities.index'));

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

    public function update_images(Request $request, string $id)
    {
        //
        // dd($request->file('images'));
        $facility = Facility::find($id);
        // dd(json_decode($facility->images));
        // $image_paths = [];
        // for($i = 0; $i < count($request->file('images')); $i++) {
        //     $path = $request->file('images')[$i]->store('facilities', 'public');
        //     array_push($image_paths, $path);
        // }
        $facility_images = json_decode($facility->images);

        foreach($request->file('images') as $img) {
            $path = $img->store('facilities', 'public');
            array_push($facility_images, $path);
        }

        $facility->images = json_encode($facility_images);
        // $facility->images = array_push($facility_images, $image_paths);
        $facility->save();
        return back();
        // $facility->images = array_push($facility->json_decode())

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
