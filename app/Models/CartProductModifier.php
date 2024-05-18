<?php

namespace App\Models;

use App\Models\CartProduct;
use App\Models\ModifierItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartProductModifier extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_product_id', 
        'modifier_group_id', 
        'modifier_item_id', 
        'quantity'
    ];

    public function cartProduct()
    {
        return $this->belongsTo(CartProduct::class);
    }

}
