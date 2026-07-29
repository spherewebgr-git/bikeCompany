<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/FeaturedBike.php
class FeaturedBike extends Model
{
    protected $guarded = [];

    public function bike()
    {
        return $this->belongsTo(Bike::class);
    }
}
