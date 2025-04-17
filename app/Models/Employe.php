<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    protected $fillable = [
        'name',
        'lastName',
        'phoneNumber',
        'role',
        'email',
        'adresse',
        'dateOfHire',
    ];

    

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function salary()
    {
        return $this->hasMany(Salary::class);
    }

    
}
