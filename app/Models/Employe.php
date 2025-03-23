<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    protected $fillable = [
        'name',
        'lastName',
        'phoneNumber',
        'salary'
    ];

    

    public function user()
    {
        return $this->hasOne(User::class);
    }

    
}
