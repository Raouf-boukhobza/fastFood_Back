<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Packs extends Model
{
    protected $fillable = [
        'name',
        'price',
        'imageUrl',
    ];
}
