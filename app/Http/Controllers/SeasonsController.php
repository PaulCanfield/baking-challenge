<?php

namespace App\Http\Controllers;

use App\Season;

class SeasonsController extends Controller
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

    public function update(Season $season) {
        $this->authorize('update', $season);

        $season->update($this->validation());

        return redirect($season->path());
    }

    public function validation() {
        return request()->validate([
            'season'   => 'required|numeric|min:1900|max:2019',
            'title'    => 'required',
            'note'     => 'nullable'
        ]);
    }
}
