<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'rolla_username',
        'happy_place',
        'country',
        'hear_rolla',
        'photo',
        'bio',
        'state_status',
        'garage',
        'following_user_id'
    ];

    public function trips()
    {
        return $this->hasMany(Trip::class, 'user_id', 'id');
    }
}
