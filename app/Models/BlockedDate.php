<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BlockedDate extends Model
{
    protected $fillable = ['bike_id', 'start_date', 'end_date', 'reason', 'created_by'];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function bike()
    {
        return $this->belongsTo(Bike::class);
    }

    public function scopeForBike($query, ?int $bikeId)
    {
        return $query->where(function ($q) use ($bikeId) {
            $q->whereNull('bike_id') // global blocks
            ->orWhere('bike_id', $bikeId);
        });
    }

    public function scopeOverlapping($query, Carbon $start, Carbon $end)
    {
        return $query->where('start_date', '<', $end)
            ->where('end_date', '>', $start);
    }
}
