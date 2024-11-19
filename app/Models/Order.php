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
    const STATUS_DELIVERED = 'delivered';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

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
                self::STATUS_DELIVERED,
                self::STATUS_COMPLETED,
                self::STATUS_CANCELLED,
            ]
        ];
    }

    protected $fillable = [
        'user_id',
        'cart_id',
        'driver_id',
        'status',
        'payment_method',
        'payment_id',
        'delivery_fee',
        'mode',
        'completed_at',
        'waiting_time',
        'total'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function driver() : BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function cart() : BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

}
