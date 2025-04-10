<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItems extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'catégory_id',
        'isAvailable',
        'imageUrl',
    ];

    public function category()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function packDetails()
    {
        return $this->hasMany(PackDetails::class);
    }
}
