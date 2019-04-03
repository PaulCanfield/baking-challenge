<h3>Episode: {{ $episode->episode }} - {{ $episode->title }}</h3>

<div class="results">
    <div class="mb-2">
        @forelse($episode->results as $result)
            <div class="result">
                @includeFirst(["seasons.result._{$result->result->key}", 'seasons.result._default'], ['result' => $result])
            </div>
        @empty
            <div class="text-red italic text-sm">
                No Results for this episode yet...
            </div>
        @endforelse
    </div>

    <form method="POST" class="shadow-outline p-2 flex" action="{{ $episode->path() }}/result">
        @csrf

        <div class="w-1/2">
            <div class="mb-3 flex items-center justify-between">
                @if(count($results = App\Result::all()))
                    <label class="font-bold" for="result">Result</label>
                    <select name="result_id" class="w-3/4 shadow-lg">
                        @foreach($results as $result)
                            <option value="{{ $result->id }}">{{ $result->result }}</option>
                        @endforeach
                    </select>
                @else
                    No Results found...
                @endif
            </div>

            <div class="flex items-center justify-between">
                @if($episode->bakers())
                    <label class="font-bold" for="baker_id">Baker</label>
                    <select name="baker_id" class="w-3/4 shadow-lg">
                        <option class="italic">-- no contestant --</option>
                        @foreach($episode->bakers() as $baker)
                            <option value="{{ $baker->id }}">{{ $baker->name }}</option>
                        @endforeach
                    </select>
                @else
                    <p>No Bakers Found...</p>
                @endif
            </div>

            <div class="mt-2">
                @include('errors', ['bag' => 'episodeResults'])
            </div>
        </div>
        <input type="submit" class="button" value="Add Episode Result">
    </form>
</div>
