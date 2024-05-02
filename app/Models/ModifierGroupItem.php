<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModifierGroupItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'modifier_group_id',
        'modifier_item_id'
    ];

}
