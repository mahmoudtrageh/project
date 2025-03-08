<?php

namespace Modules\Seo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
// use Modules\Seo\Database\Factories\SeoFactory;
use Spatie\Translatable\HasTranslations;

class Seo extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'route', 
        'uri',
        'title', 
        'description', 
        'keywords', 
        'canonical',
        'og_title', 
        'og_description', 
        'og_type', 
        'og_image',
        'twitter_title', 
        'twitter_description', 
        'twitter_card', 
        'twitter_image',
        'extras'
    ];

    protected $casts = [
        'extras' => 'array',
        'use_featured_image' => 'boolean',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatable = [
        'title',
        'description',
        'keywords',
        'og_title',
        'og_description',
        'twitter_title',
        'twitter_description',
    ];

    /**
     * Get the parent model that owns the SEO data.
     */
    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
