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
        $check = true;

        if (!$episode->season->getMembers()->contains($user)) {
            $check = false;
        }

        if (!$episode->userPredictions()->count()) {
            $check = false;
        }

        if (!$episode->canPredict()) {
            $check = false;
        }

        return $check;
    }

    public function finalize(User $user, Episode $episode) {
        if (!$episode->results) {
            return false;
        }

        if (!$user->is($episode->season->owner)) {
            return false;
        }
        return true;
    }

    /**
     * @param User $user
     * @param Season $season
     * @return bool
     */
    public function predict(User $user, Episode $episode)
    {
        if ($episode->episode == 1) {
            return false;
        }

        return ($episode->season->allMembers->contains($user) && $episode->canPredict($user));
    }

    public function seeResults(User $user, Episode $episode) {
        if ($episode->episode == 1) {
            return true;
        }

        return $episode->isCompleted($user);
    }

    public function deletePrediction(User $user, Episode $episode) {
        if ($episode->isCompleted($user)) {
            return false;
        }

        return $user->is(request()->prediction->owner);
    }

    public function addResults(User $user, Episode $episode) {
        return $episode->isCompleted($user) && ($episode->season->allMembers->contains($user));
    }
}
