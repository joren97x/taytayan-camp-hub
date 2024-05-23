<?php

namespace App\Models;

use App\Models\CartProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status'
    ];

    //delete if
    public function cart_products()
    {
        return $this->hasMany(CartProduct::class);
    }

}