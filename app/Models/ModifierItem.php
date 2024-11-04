<?php

namespace App\Models;

use App\Models\ModifierGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModifierItem extends Model
{
    use HasFactory, SoftDeletes;

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
