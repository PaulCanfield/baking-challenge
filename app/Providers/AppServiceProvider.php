<?php

namespace App\Providers;

use App\Episode;
use App\Observers\EpisodeObserver;
use App\Observers\ResultObserver;
use App\Result;
use Illuminate\Support\ServiceProvider;


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
        Episode::observe(EpisodeObserver::class);
        Result::observe(ResultObserver::class);
    }
}
