<?php

namespace App\Policies;

use App\User;
use App\Episode;
use Illuminate\Auth\Access\HandlesAuthorization;

class EpisodePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the episode.
     *
     * @param  \App\User $user
     * @param  \App\Episode $episode
     * @return mixed
     */
    public function view(User $user, Episode $episode)
    {
        //
    }

    /**
     * Determine whether the user can create episodes.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the episode.
     *
     * @param  \App\User $user
     * @param  \App\Episode $episode
     * @return mixed
     */
    public function update(User $user, Episode $episode)
    {
        //
    }

    /**
     * Determine whether the user can delete the episode.
     *
     * @param  \App\User $user
     * @param  \App\Episode $episode
     * @return mixed
     */
    public function delete(User $user, Episode $episode)
    {
        //
    }

    /**
     * Determine whether the user can restore the episode.
     *
     * @param  \App\User $user
     * @param  \App\Episode $episode
     * @return mixed
     */
    public function restore(User $user, Episode $episode)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the episode.
     *
     * @param  \App\User $user
     * @param  \App\Episode $episode
     * @return mixed
     */
    public function forceDelete(User $user, Episode $episode)
    {
        //
    }

    /**
     * @param User $user
     * @param Episode $episode
     * @return bool
     */
    public function complete(User $user, Episode $episode)
    {
        return ($episode->season->members->contains($user) && $episode->userPredictions());
    }

    /**
     * @param User $user
     * @param Season $season
     * @return bool
     */
    public function predict(User $user, Episode $episode)
    {
        return ($episode->season->members->contains($user) || $user->is($episode->season->owner)) && !$episode->isCompleted($user->id);
    }
}
