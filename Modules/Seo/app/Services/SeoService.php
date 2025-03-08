<?php

namespace Modules\Seo\Services;

use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;
use Modules\Cms\Models\Blog;
use Modules\Cms\Models\Page;
use Modules\Seo\Models\Seo;

class SeoService
{
    /**
     * Load SEO data for current request
     *
     * @param mixed $model Optional model that may contain SEO data
     * @return bool Whether SEO data was successfully loaded
     */
    public function loadSeoData($model = null)
    {
        $locale = app()->getLocale();
        
        // First priority - explicitly passed model
        if ($model && method_exists($model, 'seo')) {
            if (!$model->relationLoaded('seo')) {
                $model->load('seo');
            }
            
            if ($model->seo) {
                $this->applySeoFromModel($model, $model->seo);
                return true;
            } else {
                // Model exists but has no SEO record - generate SEO from model data
                $this->generateSeoFromModel($model);
                return true;
            }
        }
        
        // Second priority - detect model from current route
        $currentRoute = request()->route() ? request()->route()->getName() : null;
        $routeParams = request()->route() ? request()->route()->parameters() : [];
        
        $detectedModel = $this->detectModelFromRoute($currentRoute, $routeParams);
        if ($detectedModel && method_exists($detectedModel, 'seo')) {
            if (!$detectedModel->relationLoaded('seo')) {
                $detectedModel->load('seo');
            }
            
            if ($detectedModel->seo) {
                $this->applySeoFromModel($detectedModel, $detectedModel->seo);
                return true;
            } else {
                // Model exists but has no SEO record - generate SEO from model data
                $this->generateSeoFromModel($detectedModel);
                return true;
            }
        }
        
        // Third priority - route or path specific SEO data
        $currentUri = request()->path();
        $seo = null;
        
        if ($currentRoute) {
            $seo = Seo::where('route', $currentRoute)->first();
        }
        
        if (!$seo && $currentUri) {
            $seo = Seo::where('uri', $currentUri)->first();
        }
        
        if ($seo) {
            $this->applySeoFromStandalone($seo);
            return true;
        }
        
        // Fallback - use default website SEO
        $this->applyDefaultSeo();
        return false;
    }
    
    /**
     * Detect model from current route
     */
    protected function detectModelFromRoute($routeName, $parameters)
    {
        // Check for blog detail page - note the route name is 'blogs.show'
        if ($routeName === 'blogs.show' && isset($parameters['slug'])) {
            return Blog::with('seo')->where('slug', $parameters['slug'])->first();
        }
        
        // Page detail - uncomment when needed
        // if ($routeName === 'page.show' && isset($parameters['slug'])) {
        //     return Page::with('seo')->where('slug', $parameters['slug'])->first();
        // }
        
        return null;
    }
    
    /**
     * Generate SEO metadata from a model without specific SEO data
     */
    protected function generateSeoFromModel($model)
    {
        $locale = app()->getLocale();
        
        // Get model data
        $title = $model->getTranslation('title', $locale, false) ?? $this->getDefaultTitle();
        $description = $this->generateDescriptionFromModel($model) ?? $this->getDefaultDescription();
        
        // Set meta tags
        SEOMeta::setTitle($title);
        SEOMeta::setDescription($description);
        
        // Set keywords - use default as fallback
        $keywords = $this->getDefaultKeywords();
        if ($keywords) {
            SEOMeta::setKeywords(explode(',', $keywords));
        }
        
        // Set canonical URL
        $canonical = $this->generateCanonicalFromModel($model);
        SEOMeta::setCanonical($canonical);
        
        // Set Open Graph data
        OpenGraph::setTitle($title);
        OpenGraph::setDescription($description);
        OpenGraph::setUrl($canonical);
        
        // Determine OG type based on model
        $ogType = ($model instanceof Blog) ? 'article' : 'website';
        OpenGraph::setType($ogType);
        
        // Set OG image if available
        if (property_exists($model, 'image') && $model->image) {
            OpenGraph::addImage(asset('storage/' . $model->image));
        } elseif (settings()->get('og_image')) {
            OpenGraph::addImage(asset('storage/' . settings()->get('og_image')));
        }
        
        // Set Twitter Card data
        TwitterCard::setTitle($title);
        TwitterCard::setDescription($description);
        TwitterCard::setType(settings()->get('twitter_card', 'summary_large_image'));
        
        // Set Twitter image - same as OG
        if (property_exists($model, 'image') && $model->image) {
            TwitterCard::setImage(asset('storage/' . $model->image));
        } elseif (settings()->get('twitter_image')) {
            TwitterCard::setImage(asset('storage/' . settings()->get('twitter_image')));
        } elseif (settings()->get('og_image')) {
            TwitterCard::setImage(asset('storage/' . settings()->get('og_image')));
        }
        
        // Set JSON-LD schema
        $this->setSchemaForModel($model, $title, $description, $canonical);
    }
    
    /**
     * Apply SEO from a model's SEO relation
     */
    protected function applySeoFromModel($model, Seo $seo)
    {
        $locale = app()->getLocale();
        
        // Basic Meta Tags
        $title = $seo->getTranslation('title', $locale, false) 
            ?? $model->getTranslation('title', $locale, false) 
            ?? $this->getDefaultTitle();
            
        $description = $seo->getTranslation('description', $locale, false) 
            ?? $this->generateDescriptionFromModel($model) 
            ?? $this->getDefaultDescription();
        
        SEOMeta::setTitle($title);
        SEOMeta::setDescription($description);
        
        if ($keywords = $seo->getTranslation('keywords', $locale, false)) {
            SEOMeta::setKeywords(explode(',', $keywords));
        }
        
        // Canonical URL
        if ($seo->canonical) {
            SEOMeta::setCanonical($seo->canonical);
        } else {
            // Generate canonical URL based on model type
            $canonical = $this->generateCanonicalFromModel($model);
            SEOMeta::setCanonical($canonical);
        }
        
        // Open Graph Tags
        $ogTitle = $seo->getTranslation('og_title', $locale, false) ?? $title;
        $ogDescription = $seo->getTranslation('og_description', $locale, false) ?? $description;
        
        OpenGraph::setTitle($ogTitle);
        OpenGraph::setDescription($ogDescription);
        OpenGraph::setUrl(SEOMeta::getCanonical());
        OpenGraph::setType($seo->og_type ?? ($model instanceof Blog ? 'article' : 'website'));
        
        // OG Image
        if (!$seo->use_featured_image && $seo->og_image) {
            OpenGraph::addImage(asset('storage/' . $seo->og_image));
        } elseif (property_exists($model, 'image') && $model->image) {
            OpenGraph::addImage(asset('storage/' . $model->image));
        } elseif (settings()->get('og_image')) {
            OpenGraph::addImage(asset('storage/' . settings()->get('og_image')));
        }
        
        // Twitter Card
        $twitterTitle = $seo->getTranslation('twitter_title', $locale, false) ?? $ogTitle;
        $twitterDescription = $seo->getTranslation('twitter_description', $locale, false) ?? $ogDescription;
        
        TwitterCard::setTitle($twitterTitle);
        TwitterCard::setDescription($twitterDescription);
        TwitterCard::setType($seo->twitter_card ?? settings()->get('twitter_card', 'summary_large_image'));
        
        // Twitter Image
        if (!$seo->use_featured_image && $seo->og_image) {
            TwitterCard::setImage(asset('storage/' . $seo->og_image));
        } elseif (property_exists($model, 'image') && $model->image) {
            TwitterCard::setImage(asset('storage/' . $model->image));
        } elseif (settings()->get('twitter_image')) {
            TwitterCard::setImage(asset('storage/' . settings()->get('twitter_image')));
        } elseif (settings()->get('og_image')) {
            TwitterCard::setImage(asset('storage/' . settings()->get('og_image')));
        }
        
        // Schema markup
        $this->setSchemaForModel($model, $title, $description, SEOMeta::getCanonical());
    }
    
    /**
     * Apply SEO from a standalone SEO record (not attached to a model)
     */
    protected function applySeoFromStandalone(Seo $seo)
    {
        $locale = app()->getLocale();
        
        // Basic Meta Tags
        $title = $seo->getTranslation('title', $locale, false) ?? $this->getDefaultTitle();
        $description = $seo->getTranslation('description', $locale, false) ?? $this->getDefaultDescription();
        
        SEOMeta::setTitle($title);
        SEOMeta::setDescription($description);
        
        if ($keywords = $seo->getTranslation('keywords', $locale, false)) {
            SEOMeta::setKeywords(explode(',', $keywords));
        }
        
        // Canonical URL
        if ($seo->canonical) {
            SEOMeta::setCanonical($seo->canonical);
        } else {
            SEOMeta::setCanonical(request()->url());
        }
        
        // Open Graph Tags
        $ogTitle = $seo->getTranslation('og_title', $locale, false) ?? $title;
        $ogDescription = $seo->getTranslation('og_description', $locale, false) ?? $description;
        
        OpenGraph::setTitle($ogTitle);
        OpenGraph::setDescription($ogDescription);
        OpenGraph::setUrl(SEOMeta::getCanonical());
        OpenGraph::setType($seo->og_type ?? settings()->get('og_type', 'website'));
        
        // OG Image
        if (!$seo->use_featured_image && $seo->og_image) {
            OpenGraph::addImage(asset('storage/' . $seo->og_image));
        } elseif (settings()->get('og_image')) {
            OpenGraph::addImage(asset('storage/' . settings()->get('og_image')));
        }
        
        // Twitter Card
        $twitterTitle = $seo->getTranslation('twitter_title', $locale, false) ?? $ogTitle;
        $twitterDescription = $seo->getTranslation('twitter_description', $locale, false) ?? $ogDescription;
        
        TwitterCard::setTitle($twitterTitle);
        TwitterCard::setDescription($twitterDescription);
        TwitterCard::setType($seo->twitter_card ?? settings()->get('twitter_card', 'summary_large_image'));
        
        // Twitter Image
        if (!$seo->use_featured_image && $seo->og_image) {
            TwitterCard::setImage(asset('storage/' . $seo->og_image));
        } elseif (settings()->get('twitter_image')) {
            TwitterCard::setImage(asset('storage/' . settings()->get('twitter_image')));
        } elseif (settings()->get('og_image')) {
            TwitterCard::setImage(asset('storage/' . settings()->get('og_image')));
        }
        
        // Default Schema
        $this->setDefaultSchema();
    }
    
    /**
     * Apply default SEO settings from global settings
     */
    protected function applyDefaultSeo()
    {
        $locale = app()->getLocale();
        
        // Basic Meta Tags
        $title = $this->getDefaultTitle();
        $description = $this->getDefaultDescription();
        
        SEOMeta::setTitle($title);
        SEOMeta::setDescription($description);
        
        if ($keywords = $this->getDefaultKeywords()) {
            SEOMeta::setKeywords(explode(',', $keywords));
        }
        
        SEOMeta::setCanonical(request()->url());
        
        // Open Graph Tags
        $ogTitle = $this->getDefaultOgTitle() ?? $title;
        $ogDescription = $this->getDefaultOgDescription() ?? $description;
        
        OpenGraph::setTitle($ogTitle);
        OpenGraph::setDescription($ogDescription);
        OpenGraph::setUrl(request()->url());
        OpenGraph::setType(settings()->get('og_type', 'website'));
        
        // OG Image
        if (settings()->get('og_image')) {
            OpenGraph::addImage(asset('storage/' . settings()->get('og_image')));
        }
        
        // Twitter Card
        $twitterTitle = $this->getDefaultTwitterTitle() ?? $ogTitle;
        $twitterDescription = $this->getDefaultTwitterDescription() ?? $ogDescription;
        
        TwitterCard::setTitle($twitterTitle);
        TwitterCard::setDescription($twitterDescription);
        TwitterCard::setType(settings()->get('twitter_card', 'summary_large_image'));
        
        // Twitter Image
        if (settings()->get('twitter_image')) {
            TwitterCard::setImage(asset('storage/' . settings()->get('twitter_image')));
        } elseif (settings()->get('og_image')) {
            TwitterCard::setImage(asset('storage/' . settings()->get('og_image')));
        }
        
        // Default Schema
        $this->setDefaultSchema();
    }
    
    /**
     * Generate description from model content
     */
    protected function generateDescriptionFromModel($model)
    {
        $locale = app()->getLocale();
        
        if (method_exists($model, 'getTranslation') && 
            (property_exists($model, 'content') || method_exists($model, 'getContentAttribute'))) {
            $content = $model->getTranslation('content', $locale, false) ?? '';
            return \Illuminate\Support\Str::limit(strip_tags($content), 160);
        }
        
        return null;
    }
    
    /**
     * Generate canonical URL from model
     */
    protected function generateCanonicalFromModel($model)
    {
        if ($model instanceof Blog) {
            return route('blogs.show', $model->slug);
        }
        // elseif ($model instanceof Page) {
        //     return route('page.show', $model->slug);
        // }
        
        return request()->url();
    }
    
    /**
     * Set schema markup for a model
     */
    protected function setSchemaForModel($model, $title, $description, $url)
    {
        $locale = app()->getLocale();
        
        if ($model instanceof Blog) {
            // Article schema for blogs
            JsonLd::setType('Article');
            JsonLd::setTitle($title);
            JsonLd::setDescription($description);
            JsonLd::addValue('headline', $title);
            JsonLd::addValue('url', $url);
            
            // Add published and modified dates if available
            if (property_exists($model, 'published_at') && $model->published_at) {
                JsonLd::addValue('datePublished', $model->published_at->toIso8601String());
            }
            
            if (property_exists($model, 'updated_at') && $model->updated_at) {
                JsonLd::addValue('dateModified', $model->updated_at->toIso8601String());
            }
            
            // Add image if available
            if (property_exists($model, 'image') && $model->image) {
                JsonLd::addValue('image', asset('storage/' . $model->image));
            }
            
            // Add publisher info
            $siteName = isset(json_decode(settings()->get('site_name'))->{$locale}) 
                ? json_decode(settings()->get('site_name'))->{$locale} 
                : 'Website';
                
            JsonLd::addValue('author', [
                '@type' => 'Organization',
                'name' => $siteName
            ]);
            
            JsonLd::addValue('publisher', [
                '@type' => 'Organization',
                'name' => $siteName,
                'logo' => settings()->get('logo') 
                    ? asset('storage/' . settings()->get('logo')) 
                    : null
            ]);
        } else {
            // Default schema for other types
            $this->setDefaultSchema();
        }
    }
    
    /**
     * Set default schema markup
     */
    protected function setDefaultSchema()
    {
        $locale = app()->getLocale();
        $schemaType = settings()->get('schema_type', 'Organization');
        
        // Use custom schema if available
        if (settings()->has('custom_schema') && !empty(settings()->get('custom_schema'))) {
            try {
                $schemaData = json_decode(settings()->get('custom_schema'), true);
                if (is_array($schemaData)) {
                    JsonLd::addValues($schemaData);
                    return;
                }
            } catch (\Exception $e) {
                // If JSON parsing fails, continue with default schema
            }
        }
        
        // Default schema
        $siteName = isset(json_decode(settings()->get('site_name'))->{$locale}) 
            ? json_decode(settings()->get('site_name'))->{$locale} 
            : 'Website';
            
        JsonLd::setType($schemaType);
        JsonLd::setTitle($siteName);
        
        if ($description = $this->getDefaultDescription()) {
            JsonLd::setDescription($description);
        }
        
        JsonLd::addValue('url', url('/'));
        
        if (settings()->get('logo')) {
            JsonLd::addValue('logo', asset('storage/' . settings()->get('logo')));
        }
        
        // Add social media links if available
        $socialLinks = [];
        
        if (settings()->get('social_twitter')) {
            $socialLinks[] = settings()->get('social_twitter');
        }
        
        if (settings()->get('social_facebook')) {
            $socialLinks[] = settings()->get('social_facebook');
        }
        
        if (settings()->get('social_instagram')) {
            $socialLinks[] = settings()->get('social_instagram');
        }
        
        if (settings()->get('social_linkedin')) {
            $socialLinks[] = settings()->get('social_linkedin');
        }
        
        if (!empty($socialLinks)) {
            JsonLd::addValue('sameAs', $socialLinks);
        }
    }
    
    /**
     * Get default title from settings
     */
    protected function getDefaultTitle()
    {
        $locale = app()->getLocale();
        
        if (isset(json_decode(settings()->get('seo_title'))->{$locale})) {
            return json_decode(settings()->get('seo_title'))->{$locale};
        }
        
        if (isset(json_decode(settings()->get('site_name'))->{$locale})) {
            return json_decode(settings()->get('site_name'))->{$locale};
        }
        
        return 'Website';
    }
    
    /**
     * Get default description from settings
     */
    protected function getDefaultDescription()
    {
        $locale = app()->getLocale();
        
        if (isset(json_decode(settings()->get('seo_description'))->{$locale})) {
            return json_decode(settings()->get('seo_description'))->{$locale};
        }
        
        if (isset(json_decode(settings()->get('site_description'))->{$locale})) {
            return json_decode(settings()->get('site_description'))->{$locale};
        }
        
        return '';
    }
    
    /**
     * Get default keywords from settings
     */
    protected function getDefaultKeywords()
    {
        $locale = app()->getLocale();
        
        if (isset(json_decode(settings()->get('seo_keywords'))->{$locale})) {
            return json_decode(settings()->get('seo_keywords'))->{$locale};
        }
        
        return '';
    }
    
    /**
     * Get default OG title from settings
     */
    protected function getDefaultOgTitle()
    {
        $locale = app()->getLocale();
        
        if (isset(json_decode(settings()->get('og_title'))->{$locale})) {
            return json_decode(settings()->get('og_title'))->{$locale};
        }
        
        return null;
    }
    
    /**
     * Get default OG description from settings
     */
    protected function getDefaultOgDescription()
    {
        $locale = app()->getLocale();
        
        if (isset(json_decode(settings()->get('og_description'))->{$locale})) {
            return json_decode(settings()->get('og_description'))->{$locale};
        }
        
        return null;
    }
    
    /**
     * Get default Twitter title from settings
     */
    protected function getDefaultTwitterTitle()
    {
        $locale = app()->getLocale();
        
        if (isset(json_decode(settings()->get('twitter_title'))->{$locale})) {
            return json_decode(settings()->get('twitter_title'))->{$locale};
        }
        
        return null;
    }
    
    /**
     * Get default Twitter description from settings
     */
    protected function getDefaultTwitterDescription()
    {
        $locale = app()->getLocale();
        
        if (isset(json_decode(settings()->get('twitter_description'))->{$locale})) {
            return json_decode(settings()->get('twitter_description'))->{$locale};
        }
        
        return null;
    }
}