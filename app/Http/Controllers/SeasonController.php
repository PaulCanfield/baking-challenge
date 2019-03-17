<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeSeasonRequest;
use App\Season;

class SeasonController extends Controller
{
    public function index() {
        $seasons = auth()->user()->seasons;

        return view ('seasons.index', compact('seasons'));
    }

    public function store() {
        $season = auth()->user()
            ->seasons()->create($this->validation());

        return redirect($season->path());
    }

    public function edit(Season $season) {
        return view( 'seasons.edit', compact('season'));
    }


    public function show(Season $season) {
        if (auth()->user()->isNot($season->owner)) {
            abort(403);
        }

        return view('seasons.show', compact('season'));
    }

    public function create() {
        return view('seasons.create');
    }

    public function update(MakeSeasonRequest $request, Season $season) {
        $season->update($request->validated());

        return redirect($season->path());
    }

    public function validation() {
        return request()->validate([
            'season'   => 'sometimes|required|numeric|min:1900|max:2019',
            'title'    => 'sometimes|required',
            'note'     => 'nullable'
        ]);
    }
}
