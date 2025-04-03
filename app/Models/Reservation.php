<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'tables_id',
        'client_name',
        'client_phone',
        'date',
        'hour',
    ];

    public function table()
    {
        return $this->belongsTo(Tables::class , 'tables_id');
    }
}
