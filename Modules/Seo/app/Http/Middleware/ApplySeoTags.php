<?php

namespace Modules\Seo\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Seo\Services\SeoService;
use Symfony\Component\HttpFoundation\Response;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\Route; // Add this line
use Modules\Cms\Models\Blog;

class ApplySeoTags
{
    protected $seoService;

    public function __construct(SeoService $seoService)
    {
        $this->seoService = $seoService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Process response
        $response = $next($request);

        // Only apply to HTML responses
        if (!$this->isHtmlResponse($response)) {
            return $response;
        }

        // Get the current route info
        $routeName = Route::currentRouteName();
        $parameters = $request->route() ? $request->route()->parameters() : [];
        
        // Try to detect if this is a blog page
        $model = null;
        
        // Direct check for blog pages by slug
        if ($routeName === 'blogs.show' && isset($parameters['slug'])) {
            $model = Blog::with('seo')->where('slug', $parameters['slug'])->where('status', 'published')->first();
        }
        
        // Load appropriate SEO data
        $this->seoService->loadSeoData($model);
        
        // Get the content
        $content = $response->getContent();
        
        // Only modify if <head> tag is present
        if (is_string($content) && str_contains($content, '</head>')) {
            // Remove any existing SEO tags to prevent duplication
            $content = $this->removeExistingSeoTags($content);
            
            // Generate SEO tags
            $seoContent = SEOTools::generate();
            
            // Replace the closing head tag with our SEO content + closing head tag
            $content = str_replace('</head>', $seoContent . '</head>', $content);
            
            // Update the response content
            $response->setContent($content);
        }

        return $response;
    }

    /**
     * Check if the response is HTML
     */
    protected function isHtmlResponse(Response $response): bool
    {
        $contentType = $response->headers->get('Content-Type');
        return $contentType === 'text/html' 
            || (is_string($contentType) && str_contains($contentType, 'text/html'));
    }
    
    /**
     * Remove existing SEO tags to prevent duplication
     */
    protected function removeExistingSeoTags(string $content): string
    {
        // Define regex patterns for common SEO tags
        $patterns = [
            '/<title>.*?<\/title>/i',
            '/<meta\s+name=["\']description["\']\s+content=["\'].*?["\']\s*\/?>/i',
            '/<meta\s+name=["\']keywords["\']\s+content=["\'].*?["\']\s*\/?>/i',
            '/<meta\s+property=["\']og:.*?["\']\s+content=["\'].*?["\']\s*\/?>/i',
            '/<meta\s+name=["\']twitter:.*?["\']\s+content=["\'].*?["\']\s*\/?>/i',
        ];
        
        // Remove matching tags
        foreach ($patterns as $pattern) {
            $content = preg_replace($pattern, '', $content);
        }
        
        return $content;
    }
}