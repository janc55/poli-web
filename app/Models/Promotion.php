<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'slug',
        'title',
        'short_description',
        'full_description',
        'image_url',
        'discount_percentage',
        'discount_details',
        'start_date',
        'end_date',
        'validity_notes',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'discount_percentage' => 'decimal:2',
    ];
}
