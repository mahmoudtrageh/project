<?php

use Illuminate\Support\Facades\Route;
use Modules\Lang\Http\Controllers\LangController;

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