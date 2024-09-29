<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FacilityRating extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'facility_id',
        'rating',
        'review'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function facility() : BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

}
