<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Participant;
use App\Models\Booking;
use App\Models\CartProduct;
use App\Models\Order;
use App\Models\Product;
use App\Models\TicketOrder;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ViewController extends Controller
{
    //
    public function dashboard()
    {

        $today = now()->startOfDay();
        $endOfDay = now()->endOfDay();

    // Metrics
        $today_revenue = Booking::whereDate('created_at', today())->sum('total') +
                        Order::whereDate('created_at', today())->sum('total') +
                        TicketOrder::whereDate('created_at', today())->sum('amount');

        $total_transactions = Booking::whereDate('created_at', today())->count() +
                            Order::whereDate('created_at', today())->count() +
                            TicketOrder::whereDate('created_at', today())->count();

        $pending_payments = Booking::where('status', 'pending')->count() +
                        Order::where('status', 'pending')->count() +
                        TicketOrder::where('status', 'pending')->count();

        // $unprocessed_orders = Order::where('status', 'unprocessed')->count();

        // Payment Method Breakdown
        $payment_method_breakdown = Booking::select('payment_method', DB::raw('SUM(total) as total'))
            ->whereDate('created_at', today())
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method')
            ->merge(
                Order::select('payment_method', DB::raw('SUM(total) as total'))
                    ->whereDate('created_at', today())
                    ->groupBy('payment_method')
                    ->pluck('total', 'payment_method')
            )
            ->merge(
                TicketOrder::select('payment_method', DB::raw('SUM(amount) as total'))
                    ->whereDate('created_at', today())
                    ->groupBy('payment_method')
                    ->pluck('total', 'payment_method')
            );

            $weekly_sales = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
            ->whereBetween('created_at', [now()->subDays(6), now()])
            ->groupBy('date')
            ->pluck('total', 'date');
            // dd(Order::select('payment_method', DB::raw('SUM(total) as total'))
            // ->whereDate('created_at', today())
            // ->groupBy('payment_method')
            // ->pluck('total', 'payment_method'));
        // Recent Transactions
        // dd(Order::with('user')->first());
        $recent_transactions = Booking::with('user')
        ->select('id', 'user_id', 'total', 'created_at', 'status')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($transaction) {
                $transaction->type = 'Booking'; // Add type for bookings
                return $transaction;
            })
            ->merge(
                Order::with('user')
                ->select('id', 'user_id', 'total', 'created_at', 'status')
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get()
                    ->map(function ($transaction) {
                        $transaction->type = 'Order'; // Add type for orders
                        return $transaction;
                    })
            )
            ->merge(
                TicketOrder::with('user')
                ->select('id', 'user_id', 'amount as total', 'created_at', 'status')
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get()
                    ->map(function ($transaction) {
                        $transaction->type = 'Ticket'; // Add type for ticket orders
                        return $transaction;
                    })
            )
            ->sortByDesc('created_at') // Sort by created_at after merging
            ->values(); // Re-index after sorting

            $top_selling_products = CartProduct::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->take(5)
            ->with('product') // Assuming `OrderItem` has a relationship to `Product`
            ->get();

            $least_selling_products = Product::leftJoin('cart_products', 'products.id', '=', 'cart_products.product_id')
            ->select('products.id', 'products.name', 'products.photo', 'products.description', 'products.price', DB::raw('COALESCE(SUM(cart_products.quantity), 0) as total_quantity'))
            ->groupBy('products.id', 'products.name', 'products.photo', 'products.description', 'products.price')
            ->orderBy('total_quantity', 'asc')
            ->take(5)
            ->get();

            return Inertia::render('Cashier/Dashboard', compact(
                'today_revenue',
                'total_transactions',
                'pending_payments',
                'payment_method_breakdown',
                'recent_transactions',
                'top_selling_products',
                'least_selling_products',
                'weekly_sales'
            ));
        }

    public function profile(Request $request)
    {
        return Inertia::render('Cashier/Profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'google_maps_api_key' => config('app.google_maps_api_key')
        ]);
    }

    public function inbox() 
    {
        return Inertia::render('Cashier/Inbox', [
            'conversations' => Conversation::with('participants')->whereHas('participants', function ($query) {
                $query->where('user_id', auth()->id());
            })->get()
        ]);
    }

    public function show_conversation(User $user) 
    {
        // $existing_conversation = Conversation::whereHas('participants', function ($query) use ($order) {
        //     $query->where('user_id', $order->user_id);
        // })->whereHas('participants', function ($query) {
        //     $query->where('user_id', auth()->id());
        // })->first();

        $existing_conversation = Conversation::whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->whereHas('participants', function ($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$existing_conversation) {

            $conversation = Conversation::create();

            Participant::create([
                'user_id' => $user->id,
                'conversation_id' => $conversation->id
            ]);

            Participant::create([
                'user_id' => auth()->id(),
                'conversation_id' => $conversation->id
            ]);

            return redirect(route('conversations.show', $conversation->id));
        }

        return redirect(route('conversations.show', $existing_conversation->id));

    }

}
