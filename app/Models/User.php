<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Booking;
use App\Models\FitnessClass;
use App\Models\Gym;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignable
    |--------------------------------------------------------------------------
    */

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
        'gym_id', // 🏢 Multi-gym support
    ];

    /*
    |--------------------------------------------------------------------------
    | Hidden Fields
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'email_verified_at' => 'datetime',
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

    // 📖 User has many bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // 🏋️ User booked classes (many-to-many via bookings)
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

    // 👑 Check if admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // 🏢 Get gym name safely (no crash if null)
    public function getGymNameAttribute()
    {
        return $this->gym->name ?? 'No Gym Assigned';
    }

    // 📅 Check if membership is active
    public function isMembershipActive()
    {
        return $this->end_date && $this->end_date->isFuture();
    }
}