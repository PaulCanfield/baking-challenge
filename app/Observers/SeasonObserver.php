<?php

namespace App\Observers;

use App\Season;
use App\Activity;

class SeasonObserver
{
    /**
     * Handle the season "created" event.
     *
     * @param  \App\Season  $season
     * @return void
     */
    public function created(Season $season)
    {
        $this->recordActivity($season, 'Season Created');
    }

    /**
     * Handle the season "updated" event.
     *
     * @param  \App\Season  $season
     * @return void
     */
    public function updated(Season $season)
    {
        $this->recordActivity($season, 'Season Updated');
    }

    /**
     * Handle the season "deleted" event.
     *
     * @param  \App\Season  $season
     * @return void
     */
    public function deleted(Season $season)
    {
        //
    }

    /**
     * Handle the season "restored" event.
     *
     * @param  \App\Season  $season
     * @return void
     */
    public function restored(Season $season)
    {
        //
    }

    /**
     * Handle the season "force deleted" event.
     *
     * @param  \App\Season  $season
     * @return void
     */
    public function forceDeleted(Season $season)
    {
        //
    }

    protected function recordActivity(Season $season, $description) {
        Activity::create([
            'season_id' => $season->id,
            'description' => $description
        ]);
    }
}
