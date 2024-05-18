<?php

namespace App\Models;

use App\Models\ModifierItem;
use App\Models\CartProductModifier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'special_instruction'
    ];

    public function modifiers()
    {
        return $this->hasMany(CartProductModifier::class);
    }

}
