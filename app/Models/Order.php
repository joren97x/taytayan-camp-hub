<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PREPARING = 'preparing';
    const STATUS_READY_FOR_DELIVERY = 'ready_for_delivery';
    const STATUS_READY_FOR_PICKUP = 'ready_for_pickup';
    const STATUS_DELIVERING = 'delivering';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Payment method constants
    const PAYMENT_METHOD_GCASH = 'gcash';
    const PAYMENT_METHOD_CARD = 'card';
    const PAYMENT_METHOD_PAYMAYA = 'paymaya';
    const PAYMENT_METHOD_GRAB_PAY = 'grab_pay';
    const PAYMENT_METHOD_WALK_IN = 'walk_in';
    const PAYMENT_METHOD_CASH_ON_DELIVERY = 'cash_on_delivery';

    // Mode constants
    const MODE_DELIVERY = 'delivery';
    const MODE_PICKUP = 'pickup';

    public static function getConstants()
    {
        return [
            'statuses' => [
                // DO NOT CHANGE THE ORDER WALLAHI IT WILL RUIN THE SYSTEM
                self::STATUS_PENDING,
                self::STATUS_PREPARING,
                self::STATUS_READY_FOR_DELIVERY,
                self::STATUS_READY_FOR_PICKUP,
                self::STATUS_DELIVERING,
                self::STATUS_COMPLETED,
                self::STATUS_CANCELLED,
            ],
            'payment_methods' => [
                self::PAYMENT_METHOD_GCASH,
                self::PAYMENT_METHOD_CARD,
                self::PAYMENT_METHOD_PAYMAYA,
                self::PAYMENT_METHOD_GRAB_PAY,
                self::PAYMENT_METHOD_WALK_IN,
                self::PAYMENT_METHOD_CASH_ON_DELIVERY,
            ],
        ];
    }

    protected $fillable = [
        'user_id',
        'cart_id',
        'status',
        'payment_method',
        'mode',
        'completed_at',
        'waiting_time'
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
