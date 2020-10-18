<?php

namespace App\Http\Controllers;

use App\Http\Requests\BakerFormRequest;
use App\Models\Season;
use App\Models\Baker;

class SeasonBakersController extends Controller
{
    public function store(Season $season, BakerFormRequest $request) {
        $season->addBaker($request->validated());

        return redirect($season->path());
    }

    public function update(Baker $baker) {
        $this->authorize('update', $baker->season);

        request()->validate([
            'name' => 'required'
        ]);

        $baker->update([
            'name' => request('name')
        ]);

        return redirect($baker->season->path());
    }
}
