<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Conversation;
use App\Models\Event;
use App\Models\Facility;
use App\Models\Order;
use App\Models\Product;
use App\Models\TicketOrder;
use App\Models\User;
use App\Services\CartService;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ViewController extends Controller
{
    //
    public function home() {
        return Inertia::render('Customer/Index', [
            'products' => Product::with('modifier_groups.modifier_items')->get()
        ]);
    }

    public function search(string $query = null)
    {

        if(Auth::check() && Auth::user()->role != 'customer') {
            abort(403);
        }

        if(!$query) {
            return Inertia::render('Customer/Search');
        }

        $products = Product::where('name', 'LIKE', "%$query%")
            ->orWhere('description', 'LIKE', "%$query%")
            ->with('modifier_groups.modifier_items')
            ->where('available', true)
            ->get();

        $events = Event::where('title', 'LIKE', "%$query%")
        ->orWhere('description', 'LIKE', "%$query%")
        ->orWhere('location', 'LIKE', "%$query%")
        ->get();

        $facilities = Facility::where('name', 'LIKE', "%$query%")
            ->orWhere('description', 'LIKE', "%$query%")
            ->orWhere('amenities', 'LIKE', "%$query%")
            ->where('available', true)
            ->withCount(['facility_ratings as average_rating' => function ($query) {
                $query->select(DB::raw('coalesce(avg(rating), 0)'));
            }])
            ->get();

        return Inertia::render('Customer/Search', [
            'products' => $products,
            'events' => $events,
            'facilities' => $facilities
        ]);
    }

    public function profile(Request $request, CartService $cartService)
    {

        $active_orders = Order::with('driver')
        ->whereIn('status', [
            Order::STATUS_PENDING,
            Order::STATUS_READY_FOR_DELIVERY,
            Order::STATUS_DELIVERED,
            Order::STATUS_READY_FOR_PICKUP,
            Order::STATUS_DELIVERING,
            Order::STATUS_PREPARING,
        ])
        ->where('user_id', auth()->id())
        ->get();

        $past_orders = Order::with('driver')
        ->whereIn('status', [
            Order::STATUS_CANCELLED,
            Order::STATUS_COMPLETED,
        ])
        ->where('user_id', auth()->id())
        ->get();

        $orders = collect([$active_orders, $past_orders]);

        $orders->each(function($order_list) use ($cartService) {
            foreach ($order_list as $order) {
                // Get cart products and subtotal
                $result = $cartService->getCartLineItemsAndSubtotal($order->cart_id);
                $order->cart_products = $result['cart_products'];
                $order->subtotal = $result['subtotal'];

                // Fetch conversation between user and driver if a driver is assigned
                if ($order->driver) {
                    $order->conversation_id = Conversation::with('participants')
                        ->whereHas('participants', function ($query) {
                            $query->where('user_id', auth()->id());
                        })
                        ->whereHas('participants', function ($query) use ($order) {
                            $query->where('user_id', $order->driver_id);
                        })
                        ->first();
                }
            }
        });

        $active_bookings = Booking::with('facility')->whereIn('status', [
            Booking::STATUS_CHECKED_IN,
            Booking::STATUS_CONFIRMED,
            Booking::STATUS_PENDING,
            Booking::STATUS_CHECKED_OUT,
        ])
        ->where('user_id', auth()->id())
        ->get();

        $past_bookings = Booking::with('facility')->whereIn('status', [
            Booking::STATUS_CANCELLED,
            Booking::STATUS_COMPLETE,
        ])
        ->where('user_id', auth()->id())
        ->get();


        $today = Carbon::today();
        $active_ticket_orders = TicketOrder::with([
            'event', 
            'ticket_order_items', 
            'ticket_order_items.ticket.ticket_holder',
        ])
        ->whereHas('event', function($query) use ($today) {
            $query->where('date', '>=', $today);
        })
        ->whereIn('status', [TicketOrder::STATUS_CONFIRMED, TicketOrder::STATUS_PENDING])
        ->where('user_id', auth()->id())
        ->get();

        $past_ticket_orders = TicketOrder::with([
            'event', 
            'ticket_order_items', 
            'ticket_order_items.ticket.ticket_holder',
        ])
        ->where(function($query) use ($today) {
            $query->whereHas('event', function($subQuery) use ($today) {
                $subQuery->where('date', '<', $today);
            })
            ->orWhereIn('status', [TicketOrder::STATUS_CANCELLED, TicketOrder::STATUS_COMPLETED]);
        })
        ->where('user_id', auth()->id())
        ->get();

        return Inertia::render('Customer/Profile', [
            'active_orders' => $active_orders,
            'past_orders' => $past_orders,
            'active_bookings' => $active_bookings,
            'past_bookings' => $past_bookings,
            'active_ticket_orders' =>  $active_ticket_orders,
            'past_ticket_orders' =>  $past_ticket_orders,
        ]);
    }

    public function edit_profile(Request $request)
    {
        return Inertia::render('Customer/EditProfile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    public function inbox()
    {
        return Inertia::render('Customer/Inbox', [
            'conversations' => Conversation::with('participants')->whereHas('participants', function ($query) {
                $query->where('user_id', auth()->id());
            })->get()
        ]);
    }

    public function show_conversation(string $id)
    {
        return Inertia::render('Customer/ShowChat', [
            'conversations' => Conversation::with('participants')->whereHas('participants', function ($query) {
                $query->where('user_id', auth()->id());
            })->get(),
            'conversation' => Conversation::with('participants', 'messages.user')->find($id)
        ]);
    }

    // public function chat()
    // {
    //     return Inertia::render('Customer/Inbox', [

    //     ]);
    // }

}
