<?php

namespace App\Events\Product;

use App\Models\Order;
use App\Services\CartService;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $includeDetails;
    protected $cartService;
    /**
     * Create a new event instance.
     */
    public function __construct(Order $order, $includeDetails, CartService $cartService)
    {
        //
        // dd($order);
        $this->order = $order;
        $this->includeDetails = $includeDetails;
        $this->cartService = $cartService;
        // Conditionally load relations only when needed
       
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('orders.'.$this->order->id),
        ];
    }

    public function broadcastWith(): array
    {
        $data = [
            'id' => $this->order->id,
            'user_id' => $this->order->user_id,
            'cart_id' => $this->order->cart_id,
            'waiting_time' => $this->order->waiting_time,
            'status' => $this->order->status,
            'payment_method' => $this->order->payment_method,
            'mode' => $this->order->mode,
            'completed_at' => $this->order->completed_at,
            'deleted_at' => $this->order->deleted_at,
            'created_at' => $this->order->created_at,
            'updated_at' => $this->order->updated_at,
            'joren' => 'joreennn'
        ];
     
        // Conditionally add cart_products and subtotal
        if ($this->includeDetails) {

            $result = $this->cartService->getCartLineItemsAndSubtotal($this->order->cart_id);
            $data['cart_products'] = $result['cart_products'];
            $data['subtotal'] = $result['subtotal'];

            // $data['cart_products'] = $this->order->cart_products;
            // $data['subtotal'] = $this->order->subtotal;
        }

        return ['order' => $data];
    }

}
