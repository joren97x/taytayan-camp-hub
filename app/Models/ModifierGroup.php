<?php

namespace App\Models;

use App\Models\ModifierItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModifierGroup extends Model
{
    use HasFactory;

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

}
