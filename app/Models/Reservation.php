<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'date',
        'hour',
        'table_id',
    ];

    public function table()
    {
        return $this->belongsTo(Tables::class);
    }
}
