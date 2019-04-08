<?php

namespace App\Policies;

use App\User;
use App\Season;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResultPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the result.
     *
     * @param  \App\User  $user
     * @param  \App\Result  $result
     * @return mixed
     */
    public function view(User $user, Result $result)
    {
        //
    }

    /**
     * Determine whether the user can create results.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return Season::where(['owner_id' => $user->id])->exists();
    }

    /**
     * Determine whether the user can update the result.
     *
     * @param  \App\User  $user
     * @param  \App\Result  $result
     * @return mixed
     */
    public function update(User $user, Result $result)
    {
        //
    }

    /**
     * Determine whether the user can delete the result.
     *
     * @param  \App\User  $user
     * @param  \App\Result  $result
     * @return mixed
     */
    public function delete(User $user, Result $result)
    {
        //
    }

    /**
     * Determine whether the user can restore the result.
     *
     * @param  \App\User  $user
     * @param  \App\Result  $result
     * @return mixed
     */
    public function restore(User $user, Result $result)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the result.
     *
     * @param  \App\User  $user
     * @param  \App\Result  $result
     * @return mixed
     */
    public function forceDelete(User $user, Result $result)
    {
        //
    }
}
