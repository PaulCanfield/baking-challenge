<?php

namespace App\Http\Controllers;

use App\FinalResult;
use App\Http\Requests\MakeFinalResultRequest;
use App\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FinalResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Season $season, MakeFinalResultRequest $request)
    {
        FinalResult::create(
            array_merge($request->validated(), [
                'owner_id' => auth()->user()->id,
                'season_id' => $season->id
            ])
        );

        return redirect($season->path());
    }

    public function finalize(Season $season) {
        $this->authorize('finalizeFinalResults', $season);

        $season->finalizeFinalResults(auth()->user());

        return redirect($season->path());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FinalResult  $finalResult
     * @return \Illuminate\Http\Response
     */
    public function show(FinalResult $finalResult)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FinalResult  $finalResult
     * @return \Illuminate\Http\Response
     */
    public function edit(FinalResult $finalResult)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FinalResult  $finalResult
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FinalResult $finalResult)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FinalResult  $finalResult
     * @return \Illuminate\Http\Response
     */
    public function destroy(FinalResult $finalResult)
    {
        //
    }
}
