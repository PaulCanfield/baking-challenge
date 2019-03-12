@extends('layouts.app')

@section('content')

    <form method="POST" action="/seasons">
        @csrf

        <h1 class="heading is-1">Create Season</h1>

        <div class="field">
            <label for="title" class="label">Title</label>

            <div class="control">

                <input type="text" class="input" name="title" placeholder="Title">

            </div>
        </div>


        <div class="field">
            <label for="season" class="label">Season</label>

            <div class="control">

                <input type="text" class="input" name="season" placeholder="Season">

            </div>
        </div>

        <div class="field">
            <label for="note" class="label">Note</label>

            <div class="control">

                <input type="text" class="input" name="note" placeholder="Note">

            </div>
        </div>

        <div class="field">
            <button type="submit" class="button is-link">Create Season</button>
            <a href="/seasons">cancel</a>
        </div>

    </form>

@endsection
