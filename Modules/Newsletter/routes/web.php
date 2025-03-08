<?php

use Illuminate\Support\Facades\Route;
use Modules\Newsletter\Http\Controllers\NewsletterController;
use Modules\Newsletter\Http\Controllers\NewsletterTrackingController;
use Modules\Newsletter\Http\Controllers\SubscriberController;

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

Route::post('/subscribe', [SubscriberController::class, 'subscribe'])->name('subscribers.subscribe');
Route::get('/unsubscribe', [SubscriberController::class, 'unsubscribe'])->name('subscribers.unsubscribe');

// Newsletter management routes (protected by auth middleware)
Route::middleware('auth:admin')->group(function () {
    // Newsletter management
    Route::resource('newsletters', NewsletterController::class);
    Route::post('/newsletters/{newsletter}/send', [NewsletterController::class, 'send'])->name('newsletters.send');
    Route::get('/newsletters/{newsletter}/preview', [NewsletterController::class, 'preview'])->name('newsletters.preview');
    Route::post('/newsletters/preview-draft', [NewsletterController::class, 'previewDraft'])->name('newsletters.preview-draft');

    // Subscriber management
    Route::get('/subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');
    Route::post('/subscribers/import', [SubscriberController::class, 'import'])->name('subscribers.import');
    Route::post('/subscribers/{subscriber}/reactivate', [SubscriberController::class, 'reactivate'])->name('subscribers.reactivate');
    Route::delete('/subscribers/{subscriber}', [SubscriberController::class, 'destroy'])->name('subscribers.destroy');
    Route::post('/subscribers/{subscriber}/reactivate', [SubscriberController::class, 'reactivate'])->name('subscribers.reactivate');
});

// Newsletter tracking routes (public)
Route::get('/track/open/{trackingId}', [NewsletterTrackingController::class, 'trackOpen'])->name('newsletters.track-open');
Route::get('/track/click/{trackingId}', [NewsletterTrackingController::class, 'trackClick'])->name('newsletters.track-click');