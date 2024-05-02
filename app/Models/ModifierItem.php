<?php

namespace App\Models;

use App\Models\ModifierGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModifierItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price'
    ];

    public function modifier_groups()
    {
        return $this->belongsToMany(ModifierGroup::class, 'modifier_group_items');
    }

}
