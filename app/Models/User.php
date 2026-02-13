<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Booking;
use App\Models\FitnessClass;

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

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function fitnessClasses()
    {
        return $this->belongsToMany(
            FitnessClass::class,
            'bookings',
            'user_id',
            'fitness_class_id'
        );
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
