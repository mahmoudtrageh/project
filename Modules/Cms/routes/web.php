<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Home\HomeController;
use Illuminate\Support\Facades\Route;
use Modules\Cms\Http\Controllers\Blog\BlogController;
use Modules\Cms\Http\Controllers\CmsController;
use Modules\Cms\Http\Controllers\Faq\FaqController;
use Modules\Cms\Http\Controllers\Page\PageController;
use Modules\Cms\Http\Controllers\Project\ProjectController;

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
    Route::resource('cms', CmsController::class)->names('cms');
});

Route::post('/upload-image', [DashboardController::class, 'upload'])->name('upload.image');

Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/projects', [HomeController::class, 'projects'])->name('projects');
Route::get('/blogs', [HomeController::class, 'blogs'])->name('blogs');
Route::get('/blog-details/{blog}', [HomeController::class, 'blogDetails'])->name('blog.details');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('auth:admin')->group(function () {
        Route::resource('projects', ProjectController::class);
        Route::patch('projects/{project}/toggle-featured', [ProjectController::class, 'toggleFeatured'])->name('projects.toggle-featured');
        Route::patch('projects/{project}/toggle-status', [ProjectController::class, 'toggleStatus'])->name('projects.toggle-status');
    
        Route::resource('blogs', BlogController::class);
        Route::patch('blogs/{blog}/toggle-featured', [BlogController::class, 'toggleFeatured'])
            ->name('blogs.toggle-featured');

        Route::resource('pages', PageController::class);
        Route::resource('faqs', FaqController::class);

    });
});