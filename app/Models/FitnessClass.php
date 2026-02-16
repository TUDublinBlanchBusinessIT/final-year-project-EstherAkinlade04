<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FitnessClass extends Model
{
    protected $fillable = [
        'name',
        'description',
        'class_time',
        'capacity',
    ];

    protected $casts = [
        'class_time' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'bookings')
                    ->withPivot('payment_status', 'attended')
                    ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Computed Status
    |--------------------------------------------------------------------------
    */

    public function getStatusAttribute()
    {
        if ($this->class_time->isPast()) {
            return 'past';
        }

        if ($this->bookings_count >= $this->capacity) {
            return 'full';
        }

        return 'upcoming';
    }
}
