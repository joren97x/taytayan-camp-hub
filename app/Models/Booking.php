<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CHECKED_IN = 'checked_in';
    const STATUS_COMPLETE = 'complete';
    const STATUS_CHECKED_OUT = 'checked_out';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'facility_id',
        'user_id',
        'total',
        'status',
        'payment_method',
        'payment_id',
        'check_in',
        'check_out',
        'guests'
    ];

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_CANCELLED,
            self::STATUS_CHECKED_IN,
            self::STATUS_CHECKED_OUT,
            self::STATUS_CONFIRMED,
        ];
    }

    public function facility() : BelongsTo
    {
        return $this->belongsTo(Facility::class)->withTrashed();
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
