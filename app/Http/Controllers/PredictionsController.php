<?php

namespace App\Http\Controllers;

use App\Episode;
use App\Http\Requests\MakePredictionRequest;
use Illuminate\Http\Request;

class PredictionsController extends Controller
{
    public function store(Episode $episode, MakePredictionRequest $request) {
        $episode->addPrediction($request->validated());
        return redirect($episode->season->path());
    }
}
