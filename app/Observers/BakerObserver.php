<?php

namespace App\Observers;

use App\Baker;

class BakerObserver
{
    /**
     * Handle the baker "created" event.
     *
     * @param  \App\Baker  $baker
     * @return void
     */
    public function created(Baker $baker)
    {
        $baker->season->recordActivity('baker.created');
    }

    /**
     * Handle the baker "updated" event.
     *
     * @param  \App\Baker  $baker
     * @return void
     */
    public function updated(Baker $baker)
    {
        $baker->season->recordActivity('baker.updated');
        //
    }

    /**
     * Handle the baker "deleted" event.
     *
     * @param  \App\Baker  $baker
     * @return void
     */
    public function deleted(Baker $baker)
    {
        $baker->season->recordActivity('baker.deleted');
    }

    /**
     * Handle the baker "restored" event.
     *
     * @param  \App\Baker  $baker
     * @return void
     */
    public function restored(Baker $baker)
    {
        //
    }

    /**
     * Handle the baker "force deleted" event.
     *
     * @param  \App\Baker  $baker
     * @return void
     */
    public function forceDeleted(Baker $baker)
    {
        //
    }
}
