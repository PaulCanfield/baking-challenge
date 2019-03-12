@extends('layouts.app')

@section('content')
    <div class="pb-2 flex text-sm items-center">
        <div>
            <a class="no-underline" href="{{ url('/seasons') }}">My Seasons</a> / {{ $season->season  }} - {{ $season->title }}
        </div>

        <div class="button">
            <a href="/seasons">Go Back</a>
        </div>
    </div>

    <div class="season-info">
        <h3>{{ $season->season }} | {{ $season->title }}</h3>

        <h3>Notes:</h3>
        <textarea class="w-full shadow border-2" style="min-height: 200px;">{{ $season->note }}</textarea>

        <h3>Bakers</h3>

        <div class="bakers">
            @foreach ($season->bakers as $index => $baker)
                <div class="baker">
                    <form method="POST" class="w-full" action="{{ $baker->path() }}">
                        @csrf
                        @method('PATCH')
                        <input class="w-full" value="{{ $baker->name }}" name="name">
                    </form>
                </div>
            @endforeach

            <div class="baker">
                <form class="w-full" method="POST" action="{{ $season->path().'/baker' }}">
                    @csrf
                    <input class="w-full" type="text" placeholder="Add New Baker..." name="name">
                </form>
            </div>
        </div>

        <h3>Episodes</h3>

        <div class="episodes">
            @foreach ($season->episodes as $index => $episode)
                <div class="episode">
                    <form method="POST" class="w-full" action="{{ $episode->path() }}">
                        @csrf
                        @method('PATCH')
                        Episode: <input class="w-full" value="{{ $episode->episode }}" name="episode">
                    </form>

                    <form method="POST" class="w-full" action="{{ $episode->path() }}">
                        @csrf
                        @method('PATCH')
                        <input class="w-full" value="{{ $episode->title }}" name="title">
                    </form>
                </div>
            @endforeach

            <div class="episode">
                <form class="w-full flex" method="POST" action="{{ $season->path().'/episode' }}">
                    @csrf
                    <input class="w-1/4" type="text" placeholder="Episode Number..." name="episode">
                    <input class="w-3/4" type="text" placeholder="Episode Title..." name="title">
                    <input class="button" type="submit" value="Add New Episode">
                </form>
            </div>
        </div>

    </div>

@endsection
