<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModifierGroupItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'modifier_group_id',
        'modifier_item_id'
    ];

}
