<?php

namespace App\Http\Controllers\Customer\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketHolder;
use App\Models\TicketOrder;
use App\Models\TicketOrderItem;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Luigel\Paymongo\Facades\Paymongo;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    //
    public function success(Request $request) 
    {
        // dd($request);
        $event = Event::find($request->query('event_id'));

        $ticket_order = TicketOrder::create([
            'user_id' => $request->query('user_id'),
            'event_id' => $event->id,
            'amount' => $request->query('amount'),
            'payment_method' => $request->query('payment_method'),
        ]);
        
        if($request->payment_method == 'right_now') {
            $checkout_session = Paymongo::checkout()->find(session('checkout_id'));
            $ticket_order->payment_method = $checkout_session->payment_method_used;
            // $ticket_order->status = TicketOrder::STATUS_COMPLETED;
            $ticket_order->save();
        }

        foreach($request->query('ticket_holders') as $ticket_holder) {

            $ticket = Ticket::where('event_id', $event->id)->where('status', Ticket::STATUS_AVAILABLE)->first();

            $ticket->update([
                'user_id' => $request->query('user_id'),
                'status' => Ticket::STATUS_SOLD,
            ]);

            TicketHolder::create([
                'ticket_id' => $ticket->id,
                'name' => $ticket_holder['name']
            ]);

            TicketOrderItem::create([
                'ticket_id' => $ticket->id,
                'ticket_order_id' => $ticket_order->id,
            ]);

            $event->increment('tickets_sold');

        }

        $url = route('cashier.ticket_order.verify', ['ticket_order_id' => $ticket_order->id]);
        $qr_code = new QrCode($url);
        $writer = new PngWriter();
        $qr_code_path = 'qrcodes/' . $ticket_order->id . '.png';
        $qr_code_content = $writer->write($qr_code)->getString();
        Storage::disk('public')->put($qr_code_path, $qr_code_content);

        $ticket_order->qr_code_path = $qr_code_path;
        $ticket_order->save();

        return redirect(route('customer.tickets.index'));
    }

    public function pay(Request $request) 
    {
        $event = Event::find($request->event_id);
        
        if (!($event->tickets_sold + count($request->ticket_holders) <= $event->capacity)) {
            return back()->withErrors(['error' => 'Event is full or not found']);
        }

        $request->validate([
            'attendees' => 'required|integer|min:1',
            'event_id' => 'required|integer|exists:events,id',
            'user_id' => 'required|integer|exists:users,id',
            'payment_method' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'ticket_holders' => 'required|array|min:1',
            'ticket_holders.*.name' => 'required|string|max:255',
        ]);

        // dd($request->all());

        if($request->payment_method == 'walk_in') {
            return redirect(route('event.checkout.success') . '?' . http_build_query($request->all()));
        }
        
        // $cart = Cart::where('user_id', auth()->id())->where('status', true)->firstOrFail();
        // $line_items = [];
        // $subtotal = 0;
        // // Fetch cart products with related product and modifiers
        // $cart_products = $cart->cart_products()->with(['product', 'modifiers.modifier_item', 'modifiers.modifier_group'])->get();

        // foreach($cart_products as $cart_product) {
        //     foreach($cart_product->modifiers as $modifier) {
        //         $modifier->total = $modifier->quantity * $modifier->modifier_item->price;
        //         $cart_product->product->price += $modifier->total;
        //     }
        //     $cart_product->total = $cart_product->quantity * $cart_product->product->price;
            $line_items = [
                [
                    'name' => $event->title . " ticket",
                    'quantity' => (int)$request->attendees,
                    'amount' => (double)$event->admission_fee * 100,
                    'currency' => 'PHP',
                    'description' => "uhh basta description, e change lang or what in the future",
                    'images' => [
                        'https://images.unsplash.com/photo-1613243555988-441166d4d6fd?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80'
                    ],
                ]
            ];
        //     $subtotal += $cart_product->total;
        // }
        
        $checkout = Paymongo::checkout()->create([
            'cancel_url' => route('customer.events.index'),
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
            'success_url' => route('event.checkout.success') . '?' . http_build_query($request->all()),
            'statement_descriptor' => 'Laravel Paymongo Library',
            'metadata' => [
                'Key' => 'Value'
            ]
        ]);

        session(['checkout_id' => $checkout->id]);

        return Inertia::location($checkout->checkout_url);
    }
}
