<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackDetails extends Model
{
    protected $fillable = [
        'pack_id',
        'item_id',
        'quantity',
    ];


    public function pack()
    {
        return $this->belongsTo(Packs::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItems::class);
    }
}
