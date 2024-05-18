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

    public function products()
    {
        return $this->hasMany(CartProduct::class);
    }

}
