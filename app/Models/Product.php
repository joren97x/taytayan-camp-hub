<?php

namespace App\Models;

use App\Models\Category;
use App\Models\ModifierGroupProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'photo',
        'is_featured',
        'available',
        'price'
    ];

    //i added these hints if something went wrong delete it
    public function categories() : BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_products')->whereNull('category_products.deleted_at');
    }

    public function modifier_groups() : BelongsToMany
    {
        return $this->belongsToMany(ModifierGroup::class, 'modifier_group_products')->whereNull('modifier_group_products.deleted_at');
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class);
    }

    public function cart_product(): HasMany
    {
        return $this->hasMany(CartProduct::class);
    }

}
