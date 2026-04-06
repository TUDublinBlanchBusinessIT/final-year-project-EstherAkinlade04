<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gym extends Model
{
    protected $fillable = ['name', 'location'];

    public function classes()
    {
        return $this->hasMany(FitnessClass::class);
    }
}