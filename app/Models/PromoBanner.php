<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoBanner extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'banner_color',
        'content_color',
        'button_text',
        'button_link',
        'sort_order',
        'is_active',
    ];
}
