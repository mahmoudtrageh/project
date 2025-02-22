<?php

namespace Modules\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Cms\Database\Factories\BlogFactory;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'status',
        'published_at',
        'featured'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'featured' => 'boolean',
    ];

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($blog) {
            if (! $blog->slug) {
                $blog->slug = Str::slug($blog->title);
            }
        });
    }

    // Scope for published posts
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    // Scope for featured posts
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }
}
