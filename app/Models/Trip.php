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
        'trip_sound'
    ];
}
