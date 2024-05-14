<?php

namespace App\Models;

use App\Models\Category;
use App\Models\ModifierGroupProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'photo',
        'price'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_products');
    }

    public function modifier_groups()
    {
        return $this->belongsToMany(ModifierGroup::class, 'modifier_group_products');
    }

}
