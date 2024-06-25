<?php

namespace App\Models;

use App\Models\CartProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'status'
    ];

    //delete if
    public function cart_products()
    {
        return $this->hasMany(CartProduct::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
