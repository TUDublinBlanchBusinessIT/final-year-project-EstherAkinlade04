<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relationship: user bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Helper: check if admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
