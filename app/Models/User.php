<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // A user has many bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // A user belongs to many fitness classes through bookings
    public function fitnessClasses()
    {
        return $this->belongsToMany(
            FitnessClass::class,
            'bookings'
        );
    }
}
