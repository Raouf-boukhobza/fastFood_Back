<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = [
        'employe_id',
        'amount',
        'payment_date',
        'status',
        'payment_method',
        'hours_worked',
    ];
}
