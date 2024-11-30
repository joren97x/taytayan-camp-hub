<?php

namespace App\Http\Controllers\Customer\Facility;

use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Luigel\Paymongo\Facades\Paymongo;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        // dd(User::where('role', 'cashier')->orderBy('updated_at', 'desc')->first());
        $bookings = Booking::with(['facility' => function ($query) {
            $query->withCount(['facility_ratings as average_rating' => function ($query) {
                $query->select(DB::raw('coalesce(avg(rating), 0)'));
            }]);
        }])
        ->where('user_id', auth()->id())
        ->latest()
        ->get();

        return Inertia::render('Customer/Facility/Bookings', [
            'bookings' => $bookings,
        ]);
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'facility_id' => 'required',
        //     'user_id' => 'required',
        //     'payment_method' => 'required',
        //     'guests' => 'required',
        //     'check_in' => 'required',
        //     'check_out' => 'required',
        //     'total' => 'required'
        // ]);

        // dd($request);

    }

    public function create()
    {
        //
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return Inertia::render('Customer/Facility/ShowBooking', [
            'booking' => Booking::with(['facility' => function ($query) {
                $query->withCount(['facility_ratings as average_rating' => function ($query) {
                    $query->select(DB::raw('coalesce(avg(rating), 0)'));
                }]);
            }])->findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        
    }

    public function complete(Request $request, string $id)
    {
        $booking = Booking::find($id)->update(['status' => Booking::STATUS_COMPLETE]);

        $notification = Notification::create([
            'user_id' => $booking->user_id,
            'title' => 'Booking Complete',
            'description' => 'Your Booking Has Been Completed',
            'link' => route('customer.bookings.show', $booking->id),
        ]);

        event(new Notify($notification));

        return back();
    }

    public function cancel(Request $request, string $id)
    {
        $booking = Booking::find($id);

        if($booking->payment_method != 'walk_in') {
            $payment = Paymongo::payment()->find($booking->payment_id);
            Paymongo::refund()->create([
                'amount' => $payment->amount,
                'reason' => \Luigel\Paymongo\Models\Refund::REASON_REQUESTED_BY_CUSTOMER, // `duplicate`, `fraudulent`, required by customer
                'payment_id' => $booking->payment_id
            ]);
        }

        $booking->update([
            'status' => Booking::STATUS_CANCELLED
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
