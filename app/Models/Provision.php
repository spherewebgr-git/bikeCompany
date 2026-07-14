<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provision extends Model
{

    protected $fillable = [
        'name',
    ];

    public function bikes()
    {
        return $this->hasMany(Bike::class);
    }
}
