<h3>Episode: {{ $episode->episode }} - {{ $episode->title }}</h3>

<div class="results">
    @if($episode->userPredictions()->count())
        <div class="mb-2">
            <h4>Your Predictions
            @if($episode->isCompleted(auth()->user()->id))
                <span class="text-red italic text-sm">
                    -- submitted
                </span>
            @endif
            </h4>
            @foreach ($episode->userPredictions() as $result)
                <div class="result ml-4">
                    @includeFirst(["seasons.result._{$result->result->key}", 'seasons.result._default'], ['result' => $result])
                </div>
            @endforeach
        </div>
    @endif

    @can('predict', $episode)
        <form method="POST" class="border-2 border-grey-dark p-2 flex mb-2" action="{{ $episode->path() }}/prediction">
            @include ('seasons.episode._prediction_form')
        </form>
    @endcan

    @can('complete', $episode)
        <form method="POST" class="border-2 border-grey-dark p-2 flex mb-2 items-center" action="{{ $episode->path() }}/complete">
            @csrf
            <p class="italic text-red test-sm">Once you submit your predictions you can not change them.</p>
            <input type="submit" class="button" value="Submit">
        </form>
    @endcan

    @can('seeResults', $episode)
        <div class="mb-2">
            <h4>Episode Results</h4>
            @forelse($episode->results as $result)
                <div class="result ml-4">
                    @includeFirst(["seasons.result._{$result->result->key}", 'seasons.result._default'], ['result' => $result])
                </div>
            @empty
                <div class="text-red italic text-sm">
                    No Results for this episode yet...
                </div>
            @endforelse
        </div>

        @can('addResults', $episode)
            <form method="POST" class="border-2 border-grey-dark p-2 flex" action="{{ $episode->path() }}/result">
                @include ('seasons.episode._result_form')
            </form>
        @endcan
    @else
        <div class="border-2 border-grey-dark p-2">
            @if($episode->results)
                <div class="text-red italic text-sm">
                    Complete Your Predictions for this episode to see results.
                </div>
            @else
                <div class="text-red italic text-sm">
                    Complete Your Predictions to see results for this episode when they're posted.
                </div>
            @endif
        </div>
    @endcan

</div>
