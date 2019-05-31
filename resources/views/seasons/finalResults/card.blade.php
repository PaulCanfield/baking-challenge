<div class="card mb-2">
    @foreach($season->finalPredictions() as $index => $prediction)
        @if ($index == 0)
            <h3>Final Predictions</h3>
        @endif
        <div>
            {{ $prediction->baker->name }}
            @if ($prediction->winner)
                <span class="material-icons text-sm">star</span>
            @endif
        </div>
    @endforeach

    @can('castFinalResult', $season)
        <form method="POST" action="{{ $season->path() }}/finalResult">
            @csrf

            <select name="baker_id">
                @foreach($season->bakers as $index => $baker)
                    <option value="{{ $baker->id }}">{{ $baker->name }}</option>
                @endforeach
            </select>

            <label for="winner">Season Winner</label>
            <input type="checkbox" name="winner" value="1">

            <input type="submit" class="button" value="Submit">
        </form>

        @include('errors', ['bag' => 'finalResults'])
    @endcan

    @can('finalizeFinalResults', $season)
        <form method="POST" action="{{ $season->path() }}/finalResult/finalize">
            @csrf
            <input type="submit" class="button" value="Finalize">
        </form>
    @endcan
</div>