<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = [
        'name',
        'step',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
