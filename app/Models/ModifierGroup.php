<?php

namespace App\Models;

use App\Models\ModifierItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModifierGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'required',
        'required_quantity',
        'max_quantity'
    ];

    public function modifier_items()
    {
        return $this->belongsToMany(ModifierItem::class, 'modifier_group_items');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'modifier_group_products');
    }

}
