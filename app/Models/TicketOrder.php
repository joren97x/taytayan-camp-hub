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
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_SCANNED = 'scanned';

    protected $fillable = [
        'user_id',
        'event_id',
        'amount',
        'status',
        'payment_method',
        'payment_id',
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
                self::STATUS_CANCELLED,
                self::STATUS_CONFIRMED,
                self::STATUS_SCANNED,
            ],
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class)->withTrashed();
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

}
