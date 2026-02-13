<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'fitness_class_id',
    ];

    // A booking belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A booking belongs to a fitness class
    public function fitnessClass()
    {
        return $this->belongsTo(FitnessClass::class);
    }
}
