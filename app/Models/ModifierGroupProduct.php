<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModifierGroupProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'modifier_group_id'
    ];

}
