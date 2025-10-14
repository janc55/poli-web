<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'content', 'excerpt', 'image', 'slug', 'category', 'date', 'published'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($news) {
            $news->slug = Str::slug($news->title);
            if (!$news->excerpt) {
                $news->excerpt = Str::limit(strip_tags($news->content), 150);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }
}
