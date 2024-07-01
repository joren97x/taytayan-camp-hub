<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketOrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_id',
        'ticket_order_id'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function ticket_order()
    {
        return $this->belongsTo(TicketOrder::class);
    }

}
