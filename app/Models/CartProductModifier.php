<?php

namespace App\Models;

use App\Models\CartProduct;
use App\Models\ModifierItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartProductModifier extends Model
{
    use HasFactory, SoftDeletes;

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

    public function modifier_item()
    {
        return $this->belongsTo(ModifierItem::class);
    }

    public function modifier_group()
    {
        return $this->belongsTo(ModifierGroup::class);
    }

}
