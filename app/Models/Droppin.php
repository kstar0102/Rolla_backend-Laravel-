<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Droppin extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'trip_id',
        'stop_index',
        'image_path',
        'image_caption',
        'likes_user_id'
    ];
}
