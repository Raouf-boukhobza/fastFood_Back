<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
   
    protected $fillable = ['order_id', 'item_id', 'pack_id', 'quantity', 'price'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function item()
    {
        return $this->belongsTo(MenuItems::class);
    }

    public function pack()
    {
        return $this->belongsTo(Packs::class , 'pack_id');
    }

}
