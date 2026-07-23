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

class Bike extends Model
{

    protected $fillable = [
        'SKU',
        'quantity',
        'colour',
        'image_path',
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

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isAvailable(Carbon $start, Carbon $end): bool
    {
        return ! $this->orders()
            ->where(function ($query) use ($start, $end) {
                $query
                    ->whereBetween('rent_start', [$start, $end])
                    ->orWhereBetween('rent_end', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->where('rent_start', '<=', $start)
                            ->where('rent_end', '>=', $end);
                    });
            })
            ->exists();
    }
}
