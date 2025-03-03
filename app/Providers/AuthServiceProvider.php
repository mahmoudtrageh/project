<?php

namespace App\Providers;

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use App\Models\User;
use Modules\Booking\Http\Middleware\HotelManagerMiddleware;
use Modules\Booking\Http\Middleware\MarketerMiddleware;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define middleware aliases
        $this->app['router']->aliasMiddleware('admin', AdminMiddleware::class);
        $this->app['router']->aliasMiddleware('marketer', MarketerMiddleware::class);
        $this->app['router']->aliasMiddleware('hotel_manager', HotelManagerMiddleware::class);

        // Define gates for authorization
        Gate::define('admin', function (User $user) {
            return $user->user_type === 'admin';
        });

        Gate::define('marketer', function (User $user) {
            return $user->user_type === 'marketer' || $user->user_type === 'admin';
        });

        Gate::define('hotel_manager', function (User $user) {
            return $user->user_type === 'hotel_manager' || $user->user_type === 'admin';
        });

        Gate::define('view-booking', function (User $user, $booking) {
            return $user->user_type === 'admin' || 
                   ($user->user_type === 'marketer' && $booking->marketer_id == $user->marketerProfile->id) ||
                   ($user->user_type === 'hotel_manager' && $booking->hotel->manager_id == $user->id);
        });

        Gate::define('edit-booking', function (User $user, $booking) {
            return $user->user_type === 'admin' || 
                   ($user->user_type === 'marketer' && $booking->marketer_id == $user->marketerProfile->id);
        });

        Gate::define('manage-payments', function (User $user) {
            return $user->user_type === 'admin';
        });

        Gate::define('view-reports', function (User $user) {
            return $user->user_type === 'admin' || $user->user_type === 'marketer';
        });

        // Define Blade directives for easier authorization checks in views
        Blade::if('admin', function () {
            return Auth::check() && Auth::user()->user_type === 'admin';
        });

        Blade::if('marketer', function () {
            return Auth::check() && (Auth::user()->user_type === 'marketer' || Auth::user()->user_type === 'admin');
        });

        Blade::if('hotelManager', function () {
            return Auth::check() && (Auth::user()->user_type === 'hotel_manager' || Auth::user()->user_type === 'admin');
        });
    }
}