<ul class="list-reset text-sm">

    @foreach($season->activities as $activity)
        <li class="{{ $loop->last ? '' : 'mb-1' }}">
            @include("seasons.activity.{$activity->description}")
            <span class="text-grey">{{ $activity->created_at->diffForHumans(null, true) }}</span>
        </li>
    @endforeach

</ul>