<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'bike_id',
        'image',
    ];

    public function bike()
    {
        return $this->belongsTo(Bike::class);
    }

    public function getImageAttribute($value)
    {
        return asset($value);
    }
}
