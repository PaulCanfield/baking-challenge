<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvitationFormRequest;
use App\Season;
use App\User;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function store(Season $season, InvitationFormRequest $request) {
//        $this->authorize('update', $season);
//
//        request()->validate([
//            'email' => [ 'required', 'exists:users,email' ]
//        ], [
//            'email.exists' => 'The invited user must have an account.'
//        ]);

        $user = User::where($request->validated())->first();

        $season->invite($user);

        redirect($season->path());
    }
}
