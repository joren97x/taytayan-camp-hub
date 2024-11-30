<?php

namespace App\Http\Controllers\Admin\Facility;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Facility;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ViewController extends Controller
{
    //
    public function bookings()
    {
        return Inertia::render('Admin/Facility/Bookings', [
            'bookings' => Booking::with(['facility', 'user'])->get(),
            'statuses' => Booking::getStatuses()
        ]);
    }

    public function dashboard()
    {

        $revenue = Booking::whereIn('status', [Booking::STATUS_COMPLETE, Booking::STATUS_CHECKED_OUT])
        ->sum('total');

        $status_counts = Booking::selectRaw('status, COUNT(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status');

        $total_facilities = Facility::count();
        $booked_facilities = Booking::whereIn('status', [
            Booking::STATUS_CHECKED_IN,
            Booking::STATUS_CONFIRMED,
            Booking::STATUS_PENDING
        ])->distinct('facility_id')->count();

        $top_facilities = Booking::selectRaw('facility_id, COUNT(*) as bookings, SUM(total) as revenue')
        ->groupBy('facility_id')
        ->orderBy('bookings', 'desc')
        ->take(5) // Top 5 facilities
        ->get();

        $payment_method_revenue = Booking::selectRaw('payment_method, SUM(total) as revenue')
        ->groupBy('payment_method')
        ->pluck('revenue', 'payment_method');

        $upcoming_check_ins = Booking::whereDate('check_in', today())
        ->where('status', Booking::STATUS_CONFIRMED)
        ->get();

        $upcoming_check_outs = Booking::whereDate('check_out', today())
        ->where('status', Booking::STATUS_CHECKED_IN)
        ->get();

        $occupancy_rate = ($booked_facilities / $total_facilities) * 100;

        return Inertia::render('Admin/Facility/Dashboard', compact(
            'revenue',
            'status_counts',
            'total_facilities',
            'booked_facilities',
            'top_facilities',
            'payment_method_revenue',
            'upcoming_check_ins',
            'upcoming_check_outs',
            'occupancy_rate'
        ));
    }

}
