<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvitationFormRequest;
use App\Models\Season;
use App\Models\User;

class InvitationController extends Controller
{
    public function store(Season $season, InvitationFormRequest $request) {
        $user = User::where($request->validated())->first();

        $season->invite($user);

        return redirect($season->path());
    }
}
