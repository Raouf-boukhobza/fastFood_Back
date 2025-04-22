<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = [
        'name',
        'unit_price',
        'current_quantity',
        'minimum_quantity',
        'type',
        'expiration_date',
        'category',
        'fournisseur',
    ];
}
