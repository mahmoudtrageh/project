<?php

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