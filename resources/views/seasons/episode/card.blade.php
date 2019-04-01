<form method="POST" class="w-full" action="{{ $episode->path() }}">
    @csrf
    @method('PATCH')
    Episode: <input class="w-full" value="{{ $episode->episode }}" name="episode">

    <input class="w-full" value="{{ $episode->title }}" name="title">
</form>

<div class="results">
    @if (count($episode->results))
        <div>
            Episode Results Incomplete
        </div>
    @else
        <div>
            Episode Results Incomplete
        </div>
    @endif

    <form method="POST" class="w-full" action="{{ $episode->path() }}/result">
        @php
            $results = \App\Result::all()
        @endphp
        <div class="mb-3">
            <div>
                @if(count($results))
                    <label for="result">Result:</label>
                    <select name="result_id">
                        @foreach($results as $result)
                            <option value="{{ $result->id }}">{{ $result->result }}</option>
                        @endforeach
                    </select>
                @else
                    No Results found...
                @endif
            </div>
            <a href="/result" class="button-sm">create new result</a>
        </div>

        <div>
            @if($episode->bakers())
                <label for="baker_id">Baker:</label>
                <select name="baker_id">
                    @foreach($episode->bakers() as $baker)
                        <option value="{{ $baker->id }}">{{ $baker->name }}</option>
                    @endforeach
                </select>
            @else
                <p>No Bakers Found...</p>
            @endif
        </div>
    </form>
</div>
