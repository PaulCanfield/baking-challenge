<?php

namespace App\Observers;

use App\Result;

class ResultObserver
{
    public function creating(Result $result) {
        $result->key = preg_replace('/ /', '_', strtolower($result->result));
    }

    /**
     * Handle the result "created" event.
     *
     * @param  \App\Result  $result
     * @return void
     */
    public function created(Result $result)
    {
        //
    }

    /**
     * Handle the result "updated" event.
     *
     * @param  \App\Result  $result
     * @return void
     */
    public function updated(Result $result)
    {
        //
    }

    /**
     * Handle the result "deleted" event.
     *
     * @param  \App\Result  $result
     * @return void
     */
    public function deleted(Result $result)
    {
        //
    }

    /**
     * Handle the result "restored" event.
     *
     * @param  \App\Result  $result
     * @return void
     */
    public function restored(Result $result)
    {
        //
    }

    /**
     * Handle the result "force deleted" event.
     *
     * @param  \App\Result  $result
     * @return void
     */
    public function forceDeleted(Result $result)
    {
        //
    }
}
