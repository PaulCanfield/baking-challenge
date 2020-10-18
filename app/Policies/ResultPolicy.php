<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Season;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResultPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the result.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Result  $result
     * @return mixed
     */
    public function view(User $user, Result $result)
    {
        //
    }

    /**
     * Determine whether the user can create results.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return Season::where(['owner_id' => $user->id])->exists();
    }

    /**
     * Determine whether the user can update the result.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Result  $result
     * @return mixed
     */
    public function update(User $user, Result $result)
    {
        //
    }

    /**
     * Determine whether the user can delete the result.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Result  $result
     * @return mixed
     */
    public function delete(User $user, Result $result)
    {
        //
    }

    /**
     * Determine whether the user can restore the result.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Result  $result
     * @return mixed
     */
    public function restore(User $user, Result $result)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the result.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Result  $result
     * @return mixed
     */
    public function forceDelete(User $user, Result $result)
    {
        //
    }
}
