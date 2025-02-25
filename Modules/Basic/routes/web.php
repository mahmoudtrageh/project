<?php

use Illuminate\Support\Facades\Route;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Basic\Http\Controllers\Category\CategoryController;

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
    Route::resource('basic', BasicController::class)->names('basic');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('auth:admin')->group(function () {
        Route::resource('categories', CategoryController::class);
    });
});