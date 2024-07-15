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
        dd($request);
        // $path = $request->file('cover_photo')[0]->store('events', 'public');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return Inertia::render('Admin/Facility/ShowFacility');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        return Inertia::render('Admin/Facility/EditFacility');
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
