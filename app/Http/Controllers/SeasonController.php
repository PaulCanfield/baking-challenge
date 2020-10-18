<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeSeasonRequest;
use App\Models\Season;

class SeasonController extends Controller
{
    public function index() {
        $seasons = auth()->user()->joinedSeasons();

        return view ('seasons.index', compact('seasons'));
    }

    public function store(MakeSeasonRequest $request) {
        $season = auth()->user()
            ->seasons()->create($request->validated());

        return redirect($season->path());
    }

    public function edit(Season $season) {
        return view( 'seasons.edit', compact('season'));
    }


    public function show(Season $season) {
        $this->authorize('view', $season);

        return view('seasons.show', compact('season'));
    }

    public function create() {
        return view('seasons.create');
    }

    public function update(MakeSeasonRequest $request, Season $season) {
        $season->update($request->validated());
        return redirect($season->path());
    }
}
