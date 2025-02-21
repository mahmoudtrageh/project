<?php

namespace Modules\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Cms\Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'client_name',
        'image',
        'completion_date',
        'url',
        'is_featured',
        'status'
    ];

    protected $casts = [
        'completion_date' => 'date',
        'is_featured' => 'boolean'
    ];

    // Auto-generate slug when title is set
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Scope for published projects
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // Scope for featured projects
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Get image URL
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image);
    }
}
