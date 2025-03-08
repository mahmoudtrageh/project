<?php

namespace Modules\Seo\Services;

use Modules\Basic\Models\Category;
use Modules\Cms\Models\Blog;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapService
{
    /**
     * Build the sitemap
     *
     * @return Sitemap
     */
    public function build(): Sitemap
    {
        $sitemap = Sitemap::create();
        
        // Add static pages
        $this->addStaticPages($sitemap);
        
        // Add dynamic content
        $this->addBlogs($sitemap);
        $this->addCategories($sitemap);
        
        return $sitemap;
    }
    
    /**
     * Export the sitemap to various formats
     *
     * @param string $format The format to export (xml, txt, html)
     * @param string|null $path The path to save to, null for response
     * @return mixed File path or response content
     */
    public function export(string $format = 'xml', ?string $path = null)
    {
        $sitemap = $this->build();
        
        switch (strtolower($format)) {
            case 'xml':
                if ($path) {
                    return $sitemap->writeToFile($path ?: public_path('sitemap.xml'));
                }
                return $sitemap->render();
                
            case 'txt':
                $content = '';
                $urls = $this->extractUrlsFromSitemap($sitemap);
                $content = implode(PHP_EOL, $urls);
                
                if ($path) {
                    file_put_contents($path ?: public_path('sitemap.txt'), $content);
                    return $path;
                }
                
                return $content;
                
            case 'html':
                $urls = $this->extractUrlsFromSitemap($sitemap);
                $content = '<html><head><title>Sitemap</title><style>body{font-family:Arial,sans-serif;max-width:1200px;margin:0 auto;padding:20px}h1{border-bottom:1px solid #eee;padding-bottom:10px}ul{list-style-type:none;padding:0}li{margin-bottom:10px;padding:5px;border-bottom:1px solid #f5f5f5}a{color:#0066cc;text-decoration:none}a:hover{text-decoration:underline}</style></head><body><h1>Sitemap</h1><ul>';
                
                foreach ($urls as $url) {
                    $content .= '<li><a href="' . $url . '">' . $url . '</a></li>';
                }
                
                $content .= '</ul><div style="margin-top:30px;color:#666;font-size:12px;border-top:1px solid #eee;padding-top:10px">Generated on ' . date('Y-m-d H:i:s') . '</div></body></html>';
                
                if ($path) {
                    file_put_contents($path ?: public_path('sitemap.html'), $content);
                    return $path;
                }
                
                return $content;
                
            default:
                throw new \InvalidArgumentException("Unsupported format: {$format}");
        }
    }
    
    /**
     * Extract URLs from sitemap
     * 
     * @param Sitemap $sitemap
     * @return array
     */
    protected function extractUrlsFromSitemap(Sitemap $sitemap): array
    {
        $urls = [];
        
        // Get the tags property which is an array of Url objects
        $tags = $sitemap->getTags();
        
        foreach ($tags as $tag) {
            if (method_exists($tag, 'getUrl')) {
                $urls[] = $tag->getUrl();
            }
        }
        
        return $urls;
    }
    
    /**
     * Add static pages to the sitemap
     *
     * @param Sitemap $sitemap
     * @return void
     */
    protected function addStaticPages(Sitemap $sitemap): void
    {
        $pages = [
            ['url' => '/', 'priority' => 1.0, 'frequency' => Url::CHANGE_FREQUENCY_DAILY],
            ['url' => '/about', 'priority' => 0.8, 'frequency' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['url' => '/contact', 'priority' => 0.8, 'frequency' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['url' => '/services', 'priority' => 0.9, 'frequency' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['url' => '/faq', 'priority' => 0.7, 'frequency' => Url::CHANGE_FREQUENCY_MONTHLY],
        ];
        
        foreach ($pages as $page) {
            $sitemap->add(
                Url::create($page['url'])
                    ->setPriority($page['priority'])
                    ->setChangeFrequency($page['frequency'])
            );
        }
    }
    
    /**
     * Add blogs to the sitemap
     *
     * @param Sitemap $sitemap
     * @return void
     */
    protected function addBlogs(Sitemap $sitemap): void
    {
        if (class_exists(Blog::class)) {
            Blog::orderBy('published_at', 'desc')
                ->each(function (Blog $blog) use ($sitemap) {
                    $sitemap->add(
                        Url::create("/blogs/{$blog->slug}")
                            ->setLastModificationDate($blog->updated_at)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.9)
                    );
                });
        }
    }
    
    /**
     * Add categories to the sitemap
     *
     * @param Sitemap $sitemap
     * @return void
     */
    protected function addCategories(Sitemap $sitemap): void
    {
        if (class_exists(Category::class)) {
            Category::all()->each(function (Category $category) use ($sitemap) {
                $sitemap->add(
                    Url::create("/categories/{$category->slug}")
                        ->setLastModificationDate($category->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.8)
                );
            });
        }
    }
}