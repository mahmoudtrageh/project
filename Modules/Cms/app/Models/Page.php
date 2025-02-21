<?php

namespace Modules\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Cms\Database\Factories\PageFactory;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'status',
        'published_at',
        'order',
        'show_in_menu'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'show_in_menu' => 'boolean',
    ];

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($page) {
            if (! $page->slug) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    // Scope for published pages
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now())
                    ->orderBy('order');
    }

    // Scope for menu items
    public function scopeInMenu($query)
    {
        return $query->where('show_in_menu', true)
                    ->where('status', 'published')
                    ->orderBy('order');
    }
}
