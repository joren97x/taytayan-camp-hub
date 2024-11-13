<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    const TYPE_ORDER = 'order';
    const TYPE_BOOKING = 'booking';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'link',
        'is_read',
        'is_clicked',
        'type'
    ];

    public function user()
    {
        $this->belongsTo(User::class);
    }

}
