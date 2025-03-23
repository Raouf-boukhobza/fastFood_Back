<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItems extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'isAvailable',
        'imageUrl'
    
    ];

    public function category()
    {
        return $this->belongsTo(Catégorie::class);
    }
}
