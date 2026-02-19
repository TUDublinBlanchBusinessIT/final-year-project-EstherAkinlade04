<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FitnessClass extends Model
{
    protected $fillable = [
        'name',
        'description',
        'class_time',
        'capacity',
        'price',
        'stripe_price_id', // Stripe-ready architecture
    ];

    protected $casts = [
        'class_time' => 'datetime',
        'price' => 'decimal:2',
    ];

    protected $appends = [
        'status',
        'spots_remaining',
        'revenue',
        'paid_bookings_count',
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
    | Query Scopes (Admin Filters)
    |--------------------------------------------------------------------------
    */

    public function scopeUpcoming(Builder $query)
    {
        return $query->where('class_time', '>=', now());
    }

    public function scopePast(Builder $query)
    {
        return $query->where('class_time', '<', now());
    }

    public function scopeFull(Builder $query)
    {
        return $query->whereColumn('capacity', '<=', 'bookings_count');
    }

    /*
    |--------------------------------------------------------------------------
    | Computed Attributes
    |--------------------------------------------------------------------------
    */

    // Status (dramatic logic upgrade)
    public function getStatusAttribute()
    {
        if ($this->class_time->isPast()) {
            return 'past';
        }

        if ($this->bookings_count >= $this->capacity) {
            return 'full';
        }

        if ($this->spots_remaining <= 3) {
            return 'almost_full';
        }

        return 'upcoming';
    }

    // Remaining spots
    public function getSpotsRemainingAttribute()
    {
        return max(0, $this->capacity - ($this->bookings_count ?? 0));
    }

    // Paid bookings count
    public function getPaidBookingsCountAttribute()
    {
        return $this->bookings()
            ->where('payment_status', 'paid')
            ->count();
    }

    // Revenue per class
    public function getRevenueAttribute()
    {
        return $this->paid_bookings_count * $this->price;
    }
}
