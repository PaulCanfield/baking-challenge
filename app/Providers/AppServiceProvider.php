<?php

namespace App\Providers;

use App\Baker;
use App\Episode;
use App\Observers\BakerObserver;
use App\Observers\EpisodeObserver;
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
    public function boot() {
    }
}
