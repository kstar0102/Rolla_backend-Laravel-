<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermsAndConditions extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'content_type', // 'text' or 'html'
        'pdf_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
