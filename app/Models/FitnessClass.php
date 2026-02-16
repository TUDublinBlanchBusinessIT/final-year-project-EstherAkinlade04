<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;

class FitnessClass extends Model
{
    protected $fillable = [
        'name',
        'description',
        'class_time',
        'capacity',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // A class has many bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // A class has many users through bookings
    public function users()
    {
        return $this->belongsToMany(User::class, 'bookings');
    }

    /*
    |--------------------------------------------------------------------------
    | Computed Status Attribute
    |--------------------------------------------------------------------------
    | Returns: upcoming | full | past
    */

    public function getStatusAttribute()
    {
        // If class date has passed
        if (Carbon::parse($this->class_time)->isPast()) {
            return 'past';
        }

        // If capacity reached
        if ($this->bookings()->count() >= $this->capacity) {
            return 'full';
        }

        // Otherwise
        return 'upcoming';
    }
}
