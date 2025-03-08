<?php

namespace Modules\Seo\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Seo\Models\Seo;

trait Seoable
{
    /**
     * Get the model's SEO data.
     */
    public function seo(): MorphOne
    {
        return $this->morphOne(Seo::class, 'seoable');
    }

    /**
     * Save SEO data for the model.
     */
    public function saveSeo(array $seoData): Seo
    {
        if ($this->seo) {
            $this->seo->update($seoData);
            return $this->seo;
        }
        
        return $this->seo()->create($seoData);
    }
}