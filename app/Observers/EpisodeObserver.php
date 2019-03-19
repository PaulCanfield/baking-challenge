<?php

namespace App\Observers;

use App\Episode;

class EpisodeObserver
{
    /**
     * Handle the episode "created" event.
     *
     * @param  \App\Episode  $episode
     * @return void
     */
    public function created(Episode $episode)
    {
        $episode->season->recordActivity('episode.created');
    }

    /**
     * Handle the episode "updated" event.
     *
     * @param  \App\Episode  $episode
     * @return void
     */
    public function updated(Episode $episode)
    {
        $episode->season->recordActivity('episode.updated');
    }

    /**
     * Handle the episode "deleted" event.
     *
     * @param  \App\Episode  $episode
     * @return void
     */
    public function deleted(Episode $episode)
    {
        $episode->season->recordActivity('episode.deleted');
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
