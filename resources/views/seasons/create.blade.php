@extends('layouts.app')

@section('content')
    <div class="bg-white w-1/2 m-auto rounded shadow p-3">
        <h1 class="heading is-1 text-center">Create New Season</h1>

        <form method="POST" action="/seasons">

            @include('seasons._form', [
                'season' => new App\Season,
                'buttonText' => 'Create Season'
            ])

        </form>
    </div>

@endsection
