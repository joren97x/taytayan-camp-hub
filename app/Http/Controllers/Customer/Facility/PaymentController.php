<?php

namespace App\Http\Controllers\Customer\Facility;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Facility;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Luigel\Paymongo\Facades\Paymongo;

class PaymentController extends Controller
{
    //

    public function success(Request $request)
    {

       
        dd($request);
        $booking = Booking::create([
            'facility_id' => $request->facility_id,
            'user_id' => $request->user()->id,
            'payment_method' => $request->payment_method,
            'guests' => $request->guests,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'total' => $request->total
        ]);

        
        if($request->payment_method == 'right_now') {
            dd('stop');
            $checkout_session = Paymongo::checkout()->find(session('checkout_id'));
            $booking->payment_method = $checkout_session->payment_method_used;
            $booking->save();
        }

        return redirect(route('customer.bookings.index'));

    }

    public function pay(Request $request)
    {
        // return Redirect::back();
        // dd(url()->previous());


        $request->validate([
            'facility_id' => 'required',
            'payment_method' => 'required',
            'guests' => 'required',
            'check_in' => 'required',
            'check_out' => 'required',
            'total' => 'required'
        ]);
        // dd($request);
        if($request->payment_method == 'walk_in') {
            // dd('annyeong');
            return redirect(route('facility.checkout.success') . '?' . http_build_query($request->all()));
        }
        // dd($request);

        $facility = Facility::find($request->facility_id);

        $line_items = [
            [
                'name' => $facility->name,
                'quantity' => 1,
                'amount' => (double)$request->total * 100,
                'currency' => 'PHP',
                'description' => "uhh basta description, e change lang or what in the future",
                'images' => [
                    'https://images.unsplash.com/photo-1613243555988-441166d4d6fd?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80'
                ],
            ]
        ];

        // dd($request);
        $checkout = Paymongo::checkout()->create([
            'cancel_url' => url()->previous(),
            'billing' => [
                'name' => $request->user()->first_name . ' ' . $request->user()->last_name,
                'email' => $request->user()->email,
                'phone' => $request->user()->phone_number,
            ],
            'description' => 'My checkout session description',
            'line_items' => $line_items,
            'payment_method_types' => [
                'atome',
                'billease',
                'card',
                'dob',
                'dob_ubp',
                'gcash',
                'grab_pay', 
                'paymaya'
            ],
            'success_url' => route('facility.checkout.success') . '?' . http_build_query($request->all()),
            'statement_descriptor' => 'Laravel Paymongo Library',
            'metadata' => [
                'Key' => 'Value'
            ]
        ]);
        session(['checkout_id' => $checkout->id]);

        return Inertia::location($checkout->checkout_url);

    }
}
