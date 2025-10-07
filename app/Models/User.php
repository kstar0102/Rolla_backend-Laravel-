<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable; 

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;
    protected $appends = ['garage'];
    protected $hidden = ['garage_raw', 'reset_code_hash', 'reset_token']; 

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
        'following_user_id',
        'block_users',
        'following_pending_userid',
        'followed_user_id',
        'tag_notification',
        'comment_notification',
        'like_notification',
        'reset_code_hash',
        'reset_code_expires_at',
        'reset_code_attempts',
        'reset_code_last_sent_at',
        'reset_token',
        'reset_token_expires_at'
    ];

    protected $casts = [
        'reset_code_expires_at'   => 'datetime',
        'reset_code_last_sent_at' => 'datetime',
        'reset_token_expires_at'  => 'datetime',
    ];

    public function trips()
    {
        return $this->hasMany(Trip::class, 'user_id', 'id');
    }

    public function getFollowingUsers()
    {
        $followingIds = collect(explode(',', $this->following_user_id))
            ->filter()
            ->map(fn($id) => intval(trim($id)))
            ->unique();

        return self::whereIn('id', $followingIds)
            ->select('id', 'rolla_username', 'first_name', 'last_name', 'photo')
            ->get();
    }

    public function getGarageDetails()
    {
        $garageIds = collect(explode(',', $this->garage))
            ->filter()
            ->map(fn($id) => intval(trim($id)))
            ->unique();

        return CarType::whereIn('id', $garageIds)
            ->select('id', 'car_type', 'logo_path')
            ->get();
    }

    // In User.php
    public function getGarageRawAttribute()
    {
        return $this->attributes['garage'] ?? '';
    }

    public function getGarageAttribute()
    {
        $garageIds = collect(explode(',', $this->attributes['garage'] ?? ''))
            ->filter()
            ->map(fn($id) => intval(trim($id)))
            ->unique();

        return CarType::whereIn('id', $garageIds)
            ->select('id', 'car_type', 'logo_path')
            ->get();
    }
}
