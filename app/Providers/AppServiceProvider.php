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
        GoogleAccount::observe(GoogleAccountSynchronizationObserver::class);
        Calendar::observe(GoogleCalendarSynchronizationObserver::class);
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
