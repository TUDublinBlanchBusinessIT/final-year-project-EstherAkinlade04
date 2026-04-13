<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Booking;
use App\Models\FitnessClass;
use App\Models\Gym;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'membership_type',
        'gym_location',
        'plan_duration',
        'start_date',
        'end_date',
        'price_paid',
        'gym_id', // ✅ ADDED (important for multi-gym)
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // 🏢 User belongs to a Gym
    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    // 📖 User bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // 🏋️ Classes via bookings
    public function fitnessClasses()
    {
        return $this->belongsToMany(
            FitnessClass::class,
            'bookings',
            'user_id',
            'fitness_class_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    // 👑 Check if user is admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}