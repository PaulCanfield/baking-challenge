@extends('layouts.app')

@section('content')
    <div class="bg-white w-1/2 m-auto rounded shadow p-3">

        <h1 class="heading is-1 text-center">Edit Season</h1>

        <form method="POST" action="{{ $season->path() }}">
            @method('PATCH')

            @include('seasons._form', [
                'buttonText' => 'Update Season'
            ])

        </form>

    </div>
@endsection

