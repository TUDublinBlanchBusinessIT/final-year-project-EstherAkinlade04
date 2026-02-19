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
        'price',
        'admin_notes',
        'is_cancelled'
    ];

    protected $casts = [
        'class_time' => 'datetime',
        'is_cancelled' => 'boolean',
    ];

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

    // 🚀 Smart Status System
    public function getStatusAttribute()
    {
        if ($this->is_cancelled) return 'cancelled';
        if ($this->class_time->isPast()) return 'past';
        if ($this->bookings_count >= $this->capacity) return 'full';
        return 'upcoming';
    }

    // 💰 Revenue per class
    public function getRevenueAttribute()
    {
        return $this->bookings
            ->where('payment_status', 'paid')
            ->count() * $this->price;
    }

    // 📊 Percentage full
    public function getFillPercentageAttribute()
    {
        if ($this->capacity == 0) return 0;

        return round(($this->bookings_count / $this->capacity) * 100);
    }
}
