<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Trip extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'user_id',
        'start_address',
        'stop_address',
        'destination_address',
        'trip_start_date',
        'trip_end_date',
        'trip_miles',
        'trip_sound',
        'trip_caption'
    ];

    public function droppins()
    {
        return $this->hasMany(Droppin::class, 'trip_id', 'id');
    }
    
    public function comments()
    {
        return $this->hasMany(Comments::class, 'trip_id', 'id');
    }
        
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
