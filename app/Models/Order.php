<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Location;
use App\Models\Status;
use App\Models\Bike;
use App\Models\Use;

class Order extends Model
{
    protected $fillable = [
        'price',
        'order_date',
        'payed_off',
        'rent_start',
        'rent_end',
        'dropoff_address',
        'bike_id', // CHECK: are they fillable?
        'user_id',
        'status_id',
        'location_id',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function bike()
    {
        return $this->belongsTo(Bike::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
