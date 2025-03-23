<?php

namespace App\Models;

use COM;
use Illuminate\Database\Eloquent\Model;

class NonPershilabeProducts extends Model
{
    protected $fillable = [
        'name',
        'unit_price',
        'category_id',
        'current_quantity',
        'minimum_quantity',
        'self_life_days',
    ];
}
