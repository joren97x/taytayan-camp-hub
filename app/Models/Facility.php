<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'images',
        'amenities',
        'rental_start',
        'rental_end',
        'available',
        'guests'
    ];

    public function facility_ratings(): HasMany
    {
        return $this->hasMany(FacilityRating::class);
    }

}
