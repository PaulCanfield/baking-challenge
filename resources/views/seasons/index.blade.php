@extends ('layouts.app')

@section('content')

    <div class="flex items-center mb-3">
        <h2 class="mr-auto">Seasons</h2>

        <div class="button">
            <a href="/season/create">Create New Season</a>
        </div>
    </div>

    <div>

        @forelse ($seasons as $index => $season)
            <div class="pb-2">
                @include('seasons.card')
            </div>
        @empty
            <div class="bg-white mb-3 rounded shadow">
                No seasons yet.
            </div>
        @endforelse

    </div>
@endsection
