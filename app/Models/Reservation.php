<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    const PAYMENT_METHOD_GCASH = 'gcash';
    const PAYMENT_METHOD_CARD = 'card';
    const PAYMENT_METHOD_PAYMAYA = 'paymaya';
    const PAYMENT_METHOD_GRAB_PAY = 'grab_pay';
    const PAYMENT_METHOD_WALK_IN = 'walk_in';

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CHECKED_IN = 'checked_in';
    const STATUS_CHECKED_OUT = 'checked_out';
    const STATUS_CANCELLED = 'cancelled';

    public static function getConstants()
    {
        return [
            'statuses' => [
                // DO NOT CHANGE THE ORDER WALLAHI IT WILL RUIN THE SYSTEM
                self::STATUS_PENDING,
                self::STATUS_CANCELLED,
                self::STATUS_CHECKED_IN,
                self::STATUS_CHECKED_OUT,
                self::STATUS_CONFIRMED
            ],
            'payment_methods' => [
                self::PAYMENT_METHOD_GCASH,
                self::PAYMENT_METHOD_CARD,
                self::PAYMENT_METHOD_PAYMAYA,
                self::PAYMENT_METHOD_GRAB_PAY,
                self::PAYMENT_METHOD_WALK_IN
            ],
        ];
    }

}
