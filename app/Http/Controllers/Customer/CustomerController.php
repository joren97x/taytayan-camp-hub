<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //
    public function add_address(Request $request) {
        $request->validate([
            'phone_number' => 'required|unique:users',
            'address' => 'required'
        ]);

        $request->user()->phone_number = $request->phone_number;
        $request->user()->address = $request->address;
        $request->user()->save();

        return back();

    }
}
