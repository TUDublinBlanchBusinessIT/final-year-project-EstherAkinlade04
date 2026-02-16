<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'fitness_class_id',
        'payment_status',
        'attended',
    ];

    protected $casts = [
        'attended' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fitnessClass()
    {
        return $this->belongsTo(FitnessClass::class);
    }
}
