<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_UPCOMING_EVENT = 'upcoming_event';
    const STATUS_PAST_EVENT = 'past_event';
    const STATUS_DRAFT = 'draft';

    protected $fillable = [
        'title',
        'description',
        'cover_photo',
        'location',
        'coords',
        'admission_fee',
        'date',
        'start_time',
        'capacity',
        'tickets_sold',
        'min_ticket',
        'max_ticket',
        'status'
    ];

}
