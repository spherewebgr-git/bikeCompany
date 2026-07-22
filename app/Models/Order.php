<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Location;
use App\Models\Status;
use App\Models\Bike;
use App\Models\Use;
use App\Models\Card;

class Order extends Model
{
    protected $fillable = [
        'price',
        'order_date',
        'payed_off',
        'rent_start',
        'rent_end',
        'dropoff_address',
        'bike_id',
        'user_id',
        'status_id',
        'location_id',
        'card_id',
        'reserved_until',
        'completed_at',
    ];

    protected $casts = [
        'payed_off' => 'boolean',
        'order_date' => 'datetime',
        'reserved_until' => 'datetime',
        'completed_at' => 'datetime',
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

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
