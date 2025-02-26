<?php

use Illuminate\Support\Facades\Route;
use Modules\Lang\Http\Controllers\LangController;
use Modules\Lang\Http\Controllers\TranslationController;

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
    Route::resource('lang', LangController::class)->names('lang');
});

Route::post('language/switch', [LangController::class, 'switchLang'])->name('language.switch');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('auth:admin')->group(function () {

        Route::resource('languages', LangController::class);
    
    });
});

Route::middleware('auth:admin')->group(function () {

    
    $config = array_merge(config('translation-manager.route'), ['namespace' => 'Barryvdh\TranslationManager']);
Route::group($config, function($router)
{
    Route::post('/lang/store', [LangController::class, 'store'])->name('lang.store');
    Route::post('/lang/update/{Language}', [LangController::class, 'update'])->name('lang.update');
    Route::get('/{groupKey?}', [LangController::class, 'getIndex'])->name('translations.index')->where('groupKey', '.*');
    Route::post('/locales/add', [LangController::class, 'postAddLocale'])->name('lang.add');
    Route::post('/locales/remove', [LangController::class, 'postRemoveLocale'])->name('lang.remove');
    Route::get('view/{groupKey?}', [LangController::class, 'getView'])->name('get.view')->where('groupKey', '.*');
    Route::post('/import', [LangController::class, 'postImport'])->name('post.import');
    Route::post('/find', [LangController::class, 'postFind'])->name('post.find');
    Route::post('/publish/{groupKey}', [LangController::class, 'postPublish'])->name('post.publish')->where('groupKey', '.*');
    Route::post('/groups/add', [LangController::class, 'postAddGroup'])->name('post.add.group');
    Route::post('/add/{groupKey}', [LangController::class, 'postAdd'])->name('post.add')->where('groupKey', '.*');
    Route::post('/edit/{groupKey}', [LangController::class, 'postEdit'])->name('post.edit')->where('groupKey', '.*');
    Route::post('/translate-missing', [LangController::class, 'postTranslateMissing'])->name('post.translation.missing');
    Route::post('/delete/{groupKey}/{translationKey}', [LangController::class, 'postDelete'])->name('post.delete')->where('groupKey', '.*');
});

});