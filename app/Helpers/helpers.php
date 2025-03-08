<?php

use Modules\Basic\Models\Category;
use Modules\Cms\Models\Blog;
use Modules\Cms\Models\Page;
use Modules\Contact\Models\Contact;

if (!function_exists('setting')) {
    function setting($key = null, $default = null) {
        if (is_null($key)) {
            return app('QCod\Settings\Setting\Setting');
        }

        if (is_array($key)) {
            return app('QCod\Settings\Setting\Setting')->set($key);
        }

        return app('QCod\Settings\Setting\Setting')->get($key, $default);
    }
}

if (!function_exists('contacts')) {
    function contacts() {
        return Contact::where('status', 'new')->get();
    }
}

if (!function_exists('getCleanContentAttribute')) {
    function getCleanContentAttribute($content) {
        $cleanContent = strip_tags($content);
        
        // Remove any remaining code-like content (optional)
        $cleanContent = preg_replace('/[{}\[\]$<>]+/', '', $cleanContent);
        
        // Get first 50 words
        $words = str_word_count($cleanContent, 1);
        $limited = array_slice($words, 0, 15);
        
        return implode(' ', $limited) . (count($words) > 15 ? '...' : '');

    }
}

if (!function_exists('getTotalRecords')) {
    function getTotalRecords(string $module) {
        switch (strtolower($module)) {
            case 'blog':
                return Blog::count();
            
            case 'page':
                return Page::count();
            
            case 'contact':
                return Contact::count();
            
            case 'category':
                return Category::count();
            
            default:
                return 0;
        }
    }
}

if (!function_exists('getAllCounts')) {
    function getAllCounts() {
        return [
            'blog' => Blog::count(),
            'page' => Page::count(),
            'contact' => Contact::count(),
            'category' => Category::count()
        ];
    }
}