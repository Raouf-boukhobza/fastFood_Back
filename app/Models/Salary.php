<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = [
        'employe_id',
        'amount',
        'last_payment_date',
        'status',
        'payment_method',
        'primes',
    ];

    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }
}
