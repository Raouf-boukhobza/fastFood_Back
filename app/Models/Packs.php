<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Packs extends Model
{
    protected $fillable = [
        'name',
        'price',
        'imageUrl',
        'is_available'
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }


    public function packDetails()
    {
        return $this->hasMany(PackDetails::class , 'pack_id');
    }
}
