<?php

namespace App\Http\Controllers;

use App\Http\Requests\BakerFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Season;
use App\Baker;

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
