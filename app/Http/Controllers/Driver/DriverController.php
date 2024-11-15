<?php

namespace App\Http\Controllers\Driver;

use App\Events\Product\DriverLocationUpdated;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    //
    public function update_location(Request $request, $driver_id)
    {
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        broadcast(new DriverLocationUpdated($driver_id, $latitude, $longitude));

        return response()->json(['status' => 'Location updated']);
    }
}
