<?php

namespace App\Http\Controllers;

use App\Episode;
use App\Season;
// use Illuminate\Support\Facades\Input;
// use Illuminate\Http\Request;

class SeasonEpisodeController extends Controller
{
    public function store(Season $season) {
        $this->authorize('update', $season);

        request()->validate([
            'title' => 'required',
            'episode' => 'required|numeric|min:1'
        ]);

        $season->addEpisode([
            'title' => request('title'),
            'episode' => request( 'episode')
        ]);

        return redirect($season->path());
    }

    public function update(Episode $episode) {
        $this->authorize('update', $episode->season);

        request()->validate([
            'episode' => 'numeric|min:1'
        ]);

        $update = [ ];
        if (request()->has('title')) {
            $update['title'] = request('title');
        }

        if (request()->has('episode')) {
            $update['episode'] = request('episode');
        }

        $episode->update($update);

        return redirect($episode->season->path());
    }
}
