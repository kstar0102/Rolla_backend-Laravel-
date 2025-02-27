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
        'stop_locations',
        'destination_address',
        'destination_text_address',
        'trip_start_date',
        'trip_end_date',
        'trip_miles',
        'trip_sound',
        'trip_caption',
        'trip_coordinates',
        'start_location',
        'destination_location',
    ];

    protected $casts = [
        'trip_coordinates' => 'array',
        'stop_locations' => 'array',
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
