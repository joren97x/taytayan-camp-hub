<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_AVAILABLE = 'available';
    const STATUS_RESERVED = 'reserved';
    const STATUS_SOLD = 'sold';
    const STATUS_USED = 'used';

    protected $fillable = [
        'event_id',
        'user_id',
        'ticket_code',
        'status',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket_holders()
    {
        return $this->hasMany(TicketHolder::class);
    }

    public function ticket_holder()
    {
        return $this->hasOne(TicketHolder::class);
    }

    public function ticket_order_items()
    {
        return $this->hasMany(TicketOrderItem::class);
    }

}
