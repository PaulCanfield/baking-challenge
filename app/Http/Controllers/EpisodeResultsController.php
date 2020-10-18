<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Http\Requests\MakeEpisodeResultsRequest;
use Illuminate\Http\Request;

class EpisodeResultsController extends Controller
{
    public function store(Episode $episode, MakeEpisodeResultsRequest $request) {
        $episode->addResult($request->validated());

        return redirect($episode->season->path());
    }
}
