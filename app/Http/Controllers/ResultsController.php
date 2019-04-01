<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeResultRequest;
use App\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class ResultsController extends Controller
{
    public function create() {
        if (!Gate::allows('create', Result::class)) {
            return response('Unauthorized', 403);
        }

        $requestReferrer = URL::previous();

        if (!session()->exists('requestReferrer')) {
            session(compact('requestReferrer'));
        }
        return view('seasons.result.create', compact('requestReferrer'));
    }

    public function store(MakeResultRequest $request) {
        Result::create($request->validated());
        return redirect(Session::pull('requestReferrer'));
    }
}
