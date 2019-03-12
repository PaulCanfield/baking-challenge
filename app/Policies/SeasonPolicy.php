<?php

namespace App\Policies;

use App\User;
use App\Season;
use Illuminate\Auth\Access\HandlesAuthorization;

class SeasonPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Season $season) {
        return $user->is($season->owner);
    }
}
