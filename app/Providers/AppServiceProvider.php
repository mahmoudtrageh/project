<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('custom-pagination');
        Paginator::defaultSimpleView('custom-pagination');

        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }

        Schema::defaultStringLength(191);

    }
}
