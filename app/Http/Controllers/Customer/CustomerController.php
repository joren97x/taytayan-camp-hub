<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //
    public function add_address(Request $request) {
        $request->validate([
            'phone_number' => 'required',
            'address' => 'required|max:255',
            'street' => 'required|max:255',
            'address_coordinates' => 'required'
        ]);

        $request->user()->update([
            'address_coordinates' => json_encode($request->address_coordinates),
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'street' => $request->street
        ]);
        // $request->user()->address_coordinates = json_encode($request->address_coordinates);
        // $request->user()->phone_number = $request->phone_number;
        // $request->user()->address = $request->address;
        // $request->user()->save();

        return back();

    }
}
