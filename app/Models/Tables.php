<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tables extends Model
{
    protected $fillable = [
        'num_table',
        'capacity'
    ];

    public function reservation(){
        return $this->hasMany(Reservation::class);
    }
}
