<?php

use Illuminate\Support\Facades\Route;
use Modules\Seo\Http\Controllers\SeoController;
use Modules\Seo\Http\Controllers\SitemapController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([], function () {
    Route::resource('seo', SeoController::class)->names('seo');
});

Route::get('sitemap/generate', [SitemapController::class, 'generate'])->name('admin.sitemap.generate');;
Route::get('sitemap/export', [SitemapController::class, 'export'])->name('admin.sitemap.export');