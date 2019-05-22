<?php

namespace App\Http\Controllers;

use App\Episode;
use App\Season;

class SeasonEpisodeController extends Controller
{
    public function store(Season $season) {
        $this->authorize('update', $season);

        request()->validate([
            'title' => 'required'
        ]);

        $season->addEpisode([
            'title' => request('title')
        ]);

        return redirect($season->path());
    }

    public function update(Episode $episode) {
        $this->authorize('update', $episode->season);

        $episode->update([
            'title' => request('title')
        ]);

        return redirect($episode->season->path());
    }

    public function finalize(Episode $episode) {
        $this->authorize('finalize', $episode);

        $episode->finalize();

        return redirect($episode->season->path());
    }
}
