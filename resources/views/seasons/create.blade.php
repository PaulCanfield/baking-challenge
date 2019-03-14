@extends('layouts.app')

@section('content')
    <div class="bg-white w-1/2 m-auto rounded shadow p-3">
        <h1 class="heading is-1 text-center">Create New Season</h1>

        <form method="POST" action="/seasons">

            @include('seasons._form', [
                'season' => new App\Season,
                'buttonText' => 'Create Season'
            ]);

        </form>
    </div>

    {{--<form method="POST" class="bg-white w-1/2 m-auto rounded shadow p-3" action="/seasons">--}}
        {{--@csrf--}}

        {{--<h1 class="heading is-1 text-center">Create Season</h1>--}}

        {{--<div class="field mb-2">--}}
            {{--<label for="title" class="label font-bold">Title</label>--}}

            {{--<div class="control">--}}

                {{--<input type="text" class="input w-full shadow border-2" name="title" placeholder="Title">--}}

            {{--</div>--}}
        {{--</div>--}}


        {{--<div class="field mb-2">--}}
            {{--<label for="season" class="label font-bold">Season</label>--}}

            {{--<div class="control">--}}

                {{--<input type="text" class="input w-full shadow border-2" name="season" placeholder="Season">--}}

            {{--</div>--}}
        {{--</div>--}}

        {{--<div class="field mb-2">--}}
            {{--<label for="note" class="label font-bold">Note</label>--}}

            {{--<div class="control">--}}
                {{--<textarea class="w-full shadow border-2" style="min-height: 200px" placeholder="Notes..." name="note"></textarea>--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<div class="field text-right">--}}
            {{--<button type="submit" class="button is-link">Create Season</button>--}}
            {{--<a class="button" href="/seasons">cancel</a>--}}
        {{--</div>--}}

    {{--</form>--}}

@endsection
