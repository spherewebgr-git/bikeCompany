<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Card extends Model
{
    protected $fillable = [
        'number',
        'exp_month',
        'exp_year',
        'cvv',
        'user_id',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
