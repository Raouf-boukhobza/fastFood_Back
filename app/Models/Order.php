<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Prompts\Table;

class Order extends Model
{
    protected $fillable = [
        "user_id",
        "date",
        'table_id',
        'status',
        'total_price',
        'type',
        'esstimation_time',
        'delivry_adress',
        'delivry_phone',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function table()
    {
        return $this->belongsTo(Tables::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
