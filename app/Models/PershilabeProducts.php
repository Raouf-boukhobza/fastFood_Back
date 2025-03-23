<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PershilabeProducts extends Model
{
    protected $fillable = [
        'name',
        'unit_price',
        'current_quantity',
        'minimum_quantity',
    ];
}
