<?php

use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;
use Modules\Booking\Http\Controllers\BookingController;
use Modules\Booking\Http\Controllers\BookingSourceController;
use Modules\Booking\Http\Controllers\HotelController;
use Modules\Booking\Http\Controllers\PaymentController;
use Modules\Booking\Http\Controllers\ReportController;
use Modules\Booking\Http\Controllers\RoomTypeController;

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
    Route::resource('booking', BookingController::class)->names('booking');
});

Route::middleware('auth:admin')->group(function () {
    Route::resource('bookings', BookingController::class);
    
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    
    Route::resource('hotels', HotelController::class);
    
    Route::resource('room-types', RoomTypeController::class)->except(['show']);
    
    Route::resource('booking-source', BookingSourceController::class)->except(['show']);
    
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/bookings', [ReportController::class, 'bookings'])->name('bookings');
        Route::get('/marketers', [ReportController::class, 'marketers'])->name('marketers');
        Route::get('/hotels', [ReportController::class, 'hotels'])->name('hotels');
        Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
        Route::get('/export/{type}', [ReportController::class, 'export'])->name('export');
    });
    
});

