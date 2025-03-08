<?php

namespace Modules\Seo\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Basic\Models\Category;
use Modules\Cms\Models\Blog;
use Modules\Seo\Services\SitemapService;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
     /**
     * The sitemap service instance
     *
     * @var SitemapService
     */
    protected $sitemapService;
    
    /**
     * Create a new controller instance
     *
     * @param SitemapService $sitemapService
     * @return void
     */
    public function __construct(SitemapService $sitemapService)
    {
        $this->sitemapService = $sitemapService;
    }

    /**
     * Generate and return the sitemap
     *
     * @return Response
     */
    public function index()
    {
        // Cache the sitemap for 24 hours to improve performance
        return Cache::remember('sitemap', 60 * 24, function () {
            $sitemap = $this->sitemapService->build();
            return $sitemap->toResponse(request());
        });
    }
    
    /**
     * Generate the sitemap and save it to a file
     *
     * @param Request $request
     * @return Response
     */
    public function generate(Request $request)
    {
        // Get format from request or default to XML
        $format = $request->input('format', 'xml');
        
        // Clear the cache
        Cache::forget('sitemap');
        
        // Generate and export the sitemap in the requested format
        $path = public_path("sitemap.{$format}");
        $this->sitemapService->export($format, $path);
        
        // Return success message to the admin panel
        if ($request->expectsJson()) {
            return response()->json(['success' => "Sitemap has been generated in {$format} format!"]);
        }
        
        return redirect("/sitemap.{$format}")->with('success', "Sitemap has been generated in {$format} format!");
    }
    
    /**
     * Export the sitemap in various formats for download
     *
     * @param Request $request
     * @return Response
     */
    public function export(Request $request)
    {
        $format = $request->input('format', 'xml');
        
        // Generate sitemap content
        $content = $this->sitemapService->export($format);
        
        $contentType = [
            'xml' => 'application/xml',
            'txt' => 'text/plain',
            'html' => 'text/html',
        ][$format] ?? 'text/plain';
        
        $filename = "sitemap.{$format}";
        
        return response($content)
            ->header('Content-Type', $contentType)
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}