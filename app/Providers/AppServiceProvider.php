<?php

namespace App\Providers;

use App\Calendar;
use App\GoogleAccount;
use App\Observers\GoogleAccountSynchronizationObserver;
use App\Observers\GoogleCalendarSynchronizationObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
