<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PREPARING = 'preparing';
    const STATUS_FOR_DELIVERY = 'for delivery';
    const STATUS_TO_BE_DELIVERED = 'to be delivered';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Payment method constants
    const PAYMENT_METHOD_GCASH = 'gcash';
    const PAYMENT_METHOD_CREDIT = 'credit';
    const PAYMENT_METHOD_PAYMAYA = 'paymaya';
    const PAYMENT_METHOD_GRAB_PAY = 'grab_pay';
    const PAYMENT_METHOD_WALK_IN = 'walk_in';
    const PAYMENT_METHOD_CASH_ON_DELIVERY = 'cash_on_delivery';

    // Mode constants
    const MODE_DELIVERY = 'delivery';
    const MODE_PICKUP = 'pickup';

    protected $fillable = [
        'user_id',
        'cart_id',
        'status',
        'payment_method',
        'mode',
        'completed_at',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cart() : BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

}
