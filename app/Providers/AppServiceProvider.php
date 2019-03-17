<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Season;
use App\Observers\SeasonObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Season::observe(SeasonObserver::class);
    }
}
