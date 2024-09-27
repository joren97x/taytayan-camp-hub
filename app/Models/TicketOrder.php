<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketOrder extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    protected $fillable = [
        'user_id',
        'event_id',
        'amount',
        'payment_method',
        'status',
        'qr_code_path'
    ];

    public static function getConstants()
    {
        return [
            'statuses' => [
                // DO NOT CHANGE THE ORDER WALLAHI IT WILL RUIN THE SYSTEM
                self::STATUS_PENDING,
                self::STATUS_COMPLETED,
                self::STATUS_FAILED,
            ],
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function ticket_order_items()
    {
        return $this->hasMany(TicketOrderItem::class);
    }

}
