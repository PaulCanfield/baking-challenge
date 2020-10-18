<div class="p-2 bg-white shadow rounded my-2">
    @if(count($results = App\Models\Result::all()))
        <ul class="list-reset text-sm">
            @foreach($results as $result)
                <li class="mb-2" value="{{ $result->id }}">
                    {{ $result->result }}
                    @if ($result->eliminated)
                        <br><span class="text-xs text-red italic">Eliminates contestant</span>
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        No Results found...
    @endif

    <div class="text-right">
        <a href="/result" class="button-sm">create new result type</a>
    </div>
</div>
