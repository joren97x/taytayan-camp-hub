<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    const STATUS_UPCOMING_EVENT = 'upcoming_event';
    const STATUS_PAST_EVENT = 'past_event';
    const STATUS_DRAFT = 'draft';

}
