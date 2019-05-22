<?php

namespace App\Observers;

use App\Episode;

class EpisodeObserver
{
    /**
     * Handle the episode creating event.
     *
     * @param Episode $episode
     */
    public function creating(Episode $episode) {
        $episode->episode = (Episode::where('season_id', '=', $episode->season_id)->max('episode') ?: 0) + 1;
    }

    /**
     * Handle the episode "created" event.
     *
     * @param  \App\Episode  $episode
     * @return void
     */
    public function created(Episode $episode)
    {
        //
    }

    /**
     * Handle the episode "updated" event.
     *
     * @param  \App\Episode  $episode
     * @return void
     */
    public function updated(Episode $episode)
    {
        //
    }

    /**
     * Handle the episode "deleted" event.
     *
     * @param  \App\Episode  $episode
     * @return void
     */
    public function deleted(Episode $episode)
    {
        //
    }

    /**
     * Handle the episode "restored" event.
     *
     * @param  \App\Episode  $episode
     * @return void
     */
    public function restored(Episode $episode)
    {
        //
    }

    /**
     * Handle the episode "force deleted" event.
     *
     * @param  \App\Episode  $episode
     * @return void
     */
    public function forceDeleted(Episode $episode)
    {
        //
    }
}
