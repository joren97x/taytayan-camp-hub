<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Booking;
use App\Models\Event;
use App\Models\Facility;
use App\Models\Ticket;
use App\Models\TicketOrder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReportController extends Controller
{
    /**
     * Dashboard function to retrieve sales and booking metrics.
     */
    // public function dashboard()
    // {
        
    //     // 1. Total Sales (All Time): Sum of all completed orders
    //     $total_sales_all_time = Order::where('status', 'completed')->sum('total');

    //     // 2. Total Sales (Today): Sum of completed orders filtered by today's date
    //     $total_sales_today = Order::where('status', 'completed')
    //         ->whereDate('completed_at', Carbon::today())
    //         ->sum('total');

    //     // 3. Total Sales (This Month): Sum of completed orders filtered by current month
    //     $total_sales_this_month = Order::where('status', 'completed')
    //         ->whereMonth('completed_at', Carbon::now()->month)
    //         ->whereYear('completed_at', Carbon::now()->year)
    //         ->sum('total');

    //     // 4. Top-Selling Product: The product with the highest total sales
    //     $top_selling_product = Product::select('products.*')
    //     ->selectRaw('SUM(cart_products.quantity) as total_quantity')
    //     ->join('cart_products', 'products.id', '=', 'cart_products.product_id')
    //     ->groupBy('products.id', 'products.name', 'products.description', 'products.photo', 'products.price', 'products.is_featured', 'products.available', 'products.deleted_at', 'products.created_at', 'products.updated_at')
    //     ->orderByDesc('total_quantity')
    //     ->first();

    //     $upcoming_event = Event::where('date', '>', Carbon::now()) // Ensure the event date is in the future
    //     ->where('status', Event::STATUS_ON_SALE) // Only active events
    //     ->orderBy('date', 'asc') // Order by date
    //     ->first();

    //     // 5. Current Bookings: Number of ongoing bookings (e.g., status 'confirmed' or 'checked_in')
    //     $current_bookings = Booking::whereIn('status', ['confirmed', 'checked_in'])->count();

    //     // Return data to the dashboard Inertia::render
    //     return Inertia::render('Admin/Dashboard', compact(
    //         'total_sales_all_time',
    //         'total_sales_today',
    //         'total_sales_this_month',
    //         'top_selling_product',
    //         'current_bookings',
    //         'upcoming_event'
    //     ));
    // }

    public function main_dashboard()
    {
        // 1. Total Sales (All Modules): Overall revenue across all three modules
        $products_sales = Order::where('status', 'completed')->sum('total');
        $tickets_sales = TicketOrder::where('status', 'completed')->sum('amount');
        $booking_revenue = Booking::where('status', 'complete')->sum('total');
        $totalSales = $products_sales + $tickets_sales + $booking_revenue;

        $order_payments = Order::select('payment_method', DB::raw('count(*) as count'))
        ->groupBy('payment_method')
        ->get();

        $booking_payments = Booking::select('payment_method', DB::raw('count(*) as count'))
            ->groupBy('payment_method')
            ->get();

        $ticket_payments = TicketOrder::select('payment_method', DB::raw('count(*) as count'))
            ->groupBy('payment_method')
            ->get();

        // Consolidate Payment Method Counts
        $payment_counts = collect($order_payments)
            ->merge($booking_payments)
            ->merge($ticket_payments)
            ->groupBy('payment_method')
            ->map(function ($items) {
                return $items->sum('count');
            });

        // 2. Sales Breakdown by Module
        $sales_breakdown = [
            'orders' => $products_sales,
            'ticket_orders' => $tickets_sales,
            'bookings' => $booking_revenue,
        ];

        // 3. Top Metrics from Each Module
        // Food Orders
        // $topFoodItem = Product::withSum('orders', 'total')
        //     ->orderByDesc('orders_sum_total')
        //     ->first();
        $top_selling_product = Product::select('products.*')
            ->selectRaw('SUM(cart_products.quantity) as total_quantity')
            ->join('cart_products', 'products.id', '=', 'cart_products.product_id')
            ->groupBy('products.id', 'products.name', 'products.description', 'products.photo', 'products.price', 'products.is_featured', 'products.available', 'products.deleted_at', 'products.created_at', 'products.updated_at')
            ->orderByDesc('total_quantity')
            ->first();
        // $foodSalesToday = Order::where('status', 'completed')
        //     ->whereDate('completed_at', Carbon::today())
        //     ->sum('total');
        // $pendingFoodOrders = Order::where('status', 'pending')->count();

        // Event Ticketing
        $upcomingEvents = Event::where('date', '>=', Carbon::today())
            ->orderBy('date', 'asc')
            ->take(5)
            ->get();
        $most_popular_event = Event::first();

        // Facility Bookings
        $currentBookings = Booking::whereIn('status', ['confirmed', 'checked_in'])->count();
        $most_booked_facility = Facility::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->first();
        $totalBookingRevenue = Booking::where('status', 'complete')->sum('total');

        // 4. Customer Growth Rate
        $newCustomersThisMonth = User::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // 5. Sales Trend (Last 6 Months)
        $salesTrend = collect();

        // Get current date and go back 6 months
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $months->put($month, 0); // Initialize with 0 for each month
        }

        // Food Orders Sales (Orders Table)
        $product_sales = Order::selectRaw("DATE_FORMAT(completed_at, '%Y-%m') as month, SUM(total) as sales")
            ->where('status', 'completed')
            ->whereBetween('completed_at', [Carbon::now()->subMonths(5)->startOfMonth(), Carbon::now()->endOfMonth()])
            ->groupBy('month')
            ->pluck('sales', 'month');

        // Event Ticket Sales (Ticket Orders Table)
        $event_sales = TicketOrder::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(amount) as sales")
            ->where('status', 'completed')
            ->whereBetween('created_at', [Carbon::now()->subMonths(5)->startOfMonth(), Carbon::now()->endOfMonth()])
            ->groupBy('month')
            ->pluck('sales', 'month');

        // Facility Bookings Sales (Bookings Table)
        $facility_sales = Booking::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total) as sales")
            ->where('status', 'complete')
            ->whereBetween('created_at', [Carbon::now()->subMonths(5)->startOfMonth(), Carbon::now()->endOfMonth()])
            ->groupBy('month')
            ->pluck('sales', 'month');

        // Structure data for the frontend
        $sales_data = [
            'product_sales' => $months->map(function ($value, $month) use ($product_sales) {
                return $product_sales[$month] ?? 0; // Fill missing months with 0
            })->values(), // Reset the keys to be sequential
            'event_sales' => $months->map(function ($value, $month) use ($event_sales) {
                return $event_sales[$month] ?? 0; // Fill missing months with 0
            })->values(), // Reset the keys to be sequential
            'facility_sales' => $months->map(function ($value, $month) use ($facility_sales) {
                return $facility_sales[$month] ?? 0; // Fill missing months with 0
            })->values(), // Reset the keys to be sequential
            // Replace the keys with properly formatted month names
            'months' => $months->keys()->map(function ($month) {
                return Carbon::createFromFormat('Y-m', $month)->format('F'); // Format as full month name
            })->values(), // Reset the keys to ensure proper output
        ];

        $orders = Order::with('user')
            ->latest('created_at')
            ->take(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'type' => 'Order',
                    'user' => $order->user->first_name . ' ' . $order->user->last_name,
                    'status' => $order->status,
                    'amount' => $order->total, // You can calculate amount based on items if needed
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
                    'user' => $booking->user->first_name . ' ' . $booking->user->last_name,
                    'status' => $booking->status,
                    'amount' => $booking->total,
                    'payment_method' => $booking->payment_method,
                    'created_at' => $booking->created_at,
                ];
            });
        // Fetch recent ticket orders
        $ticketOrders = TicketOrder::with('user', 'event', 'tickets')
        ->latest('created_at')
        ->take(10)
        ->get()
        ->map(function ($ticketOrder) {
            return [
                'id' => $ticketOrder->id,
                'type' => 'Ticket',
                // Check if user exists, else provide a default name
                'user' => $ticketOrder->user 
                ? $ticketOrder->user->first_name . ' ' . $ticketOrder->user->last_name 
                : ($ticketOrder->tickets->first()->name ?? 'No Name'),
                'status' => $ticketOrder->status, // Adjust if you have statuses
                'amount' => $ticketOrder->amount,
                'payment_method' => $ticketOrder->payment_method,
                'created_at' => $ticketOrder->created_at,
            ];
        });
        // Combine all transactions into a single collection
        $recent_transactions = collect($orders)
        ->merge($bookings)
        ->merge($ticketOrders)
        ->sortByDesc('created_at')
        ->take(10)
        ->values();

        $startDate = Carbon::now()->subMonth(); // One month before today
        $endDate = Carbon::now();              // Today
    
        // Get events within the date range
    
        $users_this_month = User::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');
        
        $user_labels = $users_this_month->keys()->toArray(); // Dates
        $user_data = $users_this_month->values()->toArray(); // Counts
        
        return Inertia::render('Admin/Dashboard', compact(
            'totalSales',
            'sales_breakdown',
            'upcomingEvents',
            'most_popular_event',
            'products_sales',
            'tickets_sales',
            'booking_revenue',
            'newCustomersThisMonth',
            'salesTrend',
            'sales_data',
            'recent_transactions',
            'payment_counts',
            'user_labels',
            'user_data'
        ));
    }

    /**
     * Function to get sales by product.
     */
    public function salesByProduct(Request $request)
    {
        // Filter by optional date range
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Fetch product sales data with quantity and revenue
        $productSales = Product::withCount(['orders' => function ($query) use ($startDate, $endDate) {
                $query->where('status', 'completed')
                    ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('completed_at', [$startDate, $endDate]);
                    });
            }])
            ->withSum(['orders' => function ($query) use ($startDate, $endDate) {
                $query->where('status', 'completed')
                    ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('completed_at', [$startDate, $endDate]);
                    });
            }], 'total')
            ->get();

        return Inertia::render('admin.sales.products', compact('productSales', 'startDate', 'endDate'));
    }

    

    /**
     * Function to export reports as needed.
     */
    public function exportReports()
    {
        // Placeholder function for exporting data
        // Logic to export data can be added here, such as generating CSVs, PDFs, etc.
        return Inertia::render('admin.reports.export');
    }
}
