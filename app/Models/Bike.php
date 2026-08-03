<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Brand;
use App\Models\Type;
use App\Models\Speed;
use App\Models\Provision;
use App\Models\Price;
use Carbon\Carbon;
use App\Models\User;

class Bike extends Model
{


    protected $fillable = [
        'SKU',
        'quantity',
        'colour',
        'brand_id',
        'type_id',
        'speed_id',
        'provision_id',
        'serialnum',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function speed()
    {
        return $this->belongsTo(Speed::class);
    }

    public function provision()
    {
        return $this->belongsTo(Provision::class);
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wishlistedByUsers()
    {
        return $this->belongsToMany(User::class, 'wishlists')
            ->withTimestamps();

    }

    public function isAvailable(Carbon $start, Carbon $end): bool
    {
        $hasBlockingOrder = $this->orders()
            ->blocking()
            ->where(function ($query) use ($start, $end) {
                $query->where('rent_start', '<', $end)
                    ->where('rent_end', '>', $start);
            })
            ->exists();

        if ($hasBlockingOrder) {
            return false;
        }

        $hasBlockedDate = \App\Models\BlockedDate::forBike($this->id)
            ->overlapping($start->copy()->startOfDay(), $end->copy())
            ->exists();

        return !$hasBlockedDate;
    }
}
