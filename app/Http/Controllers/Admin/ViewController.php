<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Conversation;
use App\Models\Order;
use App\Models\TicketOrder;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ViewController extends Controller
{
    //
    public function dashboard() {

        $orders = Order::with('user')
            ->latest('created_at')
            ->take(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'type' => 'Order',
                    'user' => $order->user->name,
                    'status' => $order->status,
                    'amount' => null, // You can calculate amount based on items if needed
                    'payment_method' => $order->payment_method,
                    'created_at' => $order->created_at,
                ];
            });
        // Fetch recent bookings
        $bookings = Booking::with('user')
            ->latest('created_at')
            ->take(10)
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'type' => 'Booking',
                    'user' => $booking->user->name,
                    'status' => $booking->status,
                    'amount' => $booking->total,
                    'payment_method' => $booking->payment_method,
                    'created_at' => $booking->created_at,
                ];
            });
        // Fetch recent ticket orders
        $ticketOrders = TicketOrder::with('user', 'event')
            ->latest('created_at')
            ->take(10)
            ->get()
            ->map(function ($ticketOrder) {
                return [
                    'id' => $ticketOrder->id,
                    'type' => 'Ticket',
                    'user' => $ticketOrder->user->name,
                    'status' => 'Purchased', // Adjust if you have statuses
                    'amount' => $ticketOrder->amount,
                    'payment_method' => $ticketOrder->payment_method,
                    'created_at' => $ticketOrder->created_at,
                ];
            });

        // Combine all transactions into a single collection
        $recentTransactions = collect($orders)
            ->merge($bookings)
            ->merge($ticketOrders)
            ->sortByDesc('created_at')
            ->take(10);

        return Inertia::render('Admin/Dashboard', [
            'recent_transactions' => $recentTransactions
        ]);
    }

    public function profile(Request $request)
    {
        return Inertia::render('Admin/Profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    public function inbox()
    {
        return Inertia::render('Admin/Inbox', [
            'users' => User::where('role', '!=', User::ROLE_ADMIN)->get(),
            'conversations' => Conversation::with('messages')->whereHas('participants', function ($query) {
                $query->where('user_id', auth()->user()->id);
            })->get()
        ]);
    }

    public function user_management() {
        return Inertia::render('Admin/UserManagement');
    }
}
