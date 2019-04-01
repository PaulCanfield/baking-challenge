@extends('layouts.app')

@section('content')

    <div class="bg-white w-1/2 m-auto rounded shadow p-3">
        <h1 class="heading is-1 text-center">Create Result</h1>

        <form method="POST" action="/result">
            @include('seasons.result._form', [
                'result' => new App\Result,
                'buttonText' => 'Create Result'
            ])
        </form>
    </div>

@endsection
