<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ModifierItem;
use App\Models\CartProductModifier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'special_instruction'
    ];

    public function modifiers(): HasMany
    {
        return $this->hasMany(CartProductModifier::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function cart():BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

}
