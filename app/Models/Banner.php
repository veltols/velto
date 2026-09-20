<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'text',
        'button_text',
        'button_link',
        'image_path',
        'is_active',
        'is_slider',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_slider' => 'boolean',
    ];
}
