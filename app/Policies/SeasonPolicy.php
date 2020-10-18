<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Season;
use Illuminate\Auth\Access\HandlesAuthorization;

class SeasonPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the season.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Season  $season
     * @return mixed
     */
    public function view(User $user, Season $season) {
        return $season->members->contains($user) || $user->is($season->owner);
    }

    /**
     * Determine whether the user can create seasons.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user) {
        return auth()->user()->is($user);
    }

    /**
     * Determine whether the user can update the season.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Season  $season
     * @return mixed
     */
    public function update(User $user, Season $season) {
        return $season->members->contains($user) || $user->is($season->owner);
    }

    public function manage(User $user, Season $season) {
        return $user->is($season->owner);
    }

    /**
     * Determine whether the user can delete the season.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Season  $season
     * @return mixed
     */
    public function delete(User $user, Season $season) {
        //
    }

    /**
     * Determine whether the user can restore the season.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Season  $season
     * @return mixed
     */
    public function restore(User $user, Season $season) {
        //
    }

    /**
     * Determine whether the user can permanently delete the season.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Season  $season
     * @return mixed
     */
    public function forceDelete(User $user, Season $season) {
        //
    }

    public function castFinalResult(User $user, Season $season) {
        if ($season->allMembers->contains($user) == false) {
            return false;
        }

        if ($season->finalPredictionsCount($user) < 3) {
            return true;
        }

        return false;
    }

    public function finalizeFinalResults(User $user, Season $season) {
        if ($season->allMembers->contains($user) == false) {
            return false;
        }

        if ($season->finalResultsFinalized($user)) {
            return false;
        }

        if ($season->finalPredictionsCount($user) == 3 && $season->predictedWinner($user)) {
            return true;
        }

        return false;
    }
}
