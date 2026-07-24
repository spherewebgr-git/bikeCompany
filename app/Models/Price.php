<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Price extends Model
{

    protected $fillable = [
        'bike_id',
        'price',
        'description',
    ];

    public function bike()
    {
        return $this->belongsTo(Bike::class);
    }
}
