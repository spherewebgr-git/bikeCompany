<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'name',
        'latitude',
        'longitude'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
