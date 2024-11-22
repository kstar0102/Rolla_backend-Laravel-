<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Comments extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'trip_id',
        'user_id',
        'content'
    ];
}
