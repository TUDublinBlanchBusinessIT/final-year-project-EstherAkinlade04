<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FitnessClass extends Model
{
    protected $fillable = [
        'name',
        'description',
        'class_time',
        'capacity',
    ];

    // A class has many bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // A class has many users through bookings
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'bookings'
        );
    }
}
