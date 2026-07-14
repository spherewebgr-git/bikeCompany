<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Speed extends Model
{

    protected $fillable = [
        'gears',
    ];

    public function bikes()
    {
        return $this->hasMany(Bike::class);
    }
}
