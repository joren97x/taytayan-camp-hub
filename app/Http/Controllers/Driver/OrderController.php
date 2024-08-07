<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    //
    public function index(CartService $cartService) {

        $orders = Order::whereIn('status', [
            Order::STATUS_READY_FOR_DELIVERY,
            Order::STATUS_DELIVERING
        ])->with('user')->get();

        foreach($orders as $order) {
            $result = $cartService->getCartLineItemsAndSubtotal(false, $order->cart_id);
            $order->cart_products = $result['cart_products'];
            $order->subtotal = $result['subtotal'];
            // $order->customer = $order->user()->get();
        }

        return Inertia::render('Driver/Orders', ['orders' => $orders]);
    }

    public function deliver($id) {
        $order = Order::find($id);
        $order->status = Order::STATUS_DELIVERING;
        $order->update();
        return redirect(route('driver.map'));
    }

    //might turn this into a service in the future cus its used to different controllers
    public function show(string $id, CartService $cartService)
    {
        $order = Order::with('user')->find($id);

        $result = $cartService->getCartLineItemsAndSubtotal(false, $order->cart_id);
        $order->cart_products = $result['cart_products'];
        $order->subtotal = $result['subtotal'];

        return response()->json($order);
    }

}
