<?php

namespace App\Models;

use App\Models\Category;
use App\Models\ModifierGroupProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'photo',
        'price'
    ];

    //i added these hints if something went wrong delete it
    public function categories() : BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_products');
    }

    public function modifier_groups() : BelongsToMany
    {
        return $this->belongsToMany(ModifierGroup::class, 'modifier_group_products');
    }

}
