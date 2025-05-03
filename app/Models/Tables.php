<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tables extends Model
{
    protected $fillable = [
        'num_table',
        'capacity',
        'status',
    ];

    public function reservations(){
        return $this->hasMany(Reservation::class);
    }
}
