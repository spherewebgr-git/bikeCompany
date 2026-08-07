<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Bike;

#[Fillable(['first_name', 'last_name', 'phone', 'email', 'password','role_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wishlistBikes()
    {
        return $this->belongsToMany(
            Bike::class,
            'wishlists'
        )->withTimestamps();
    }

    public function compareBikes()
    {
        return $this->belongsToMany(
            Bike::class,
            'compare'
        )->withTimestamps();

    }
}
