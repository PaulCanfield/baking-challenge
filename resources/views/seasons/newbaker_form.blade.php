<form class="w-full text-sm text-right" method="POST" action="{{ $season->path().'/baker' }}">
    @csrf

    <div class="mb-2">
        @include('errors', [ 'bag' => 'baker' ])
    </div>

    <input class="w-full border-4 mb-2" type="text" placeholder="New baker name..." name="name">
    <input class="button-sm" type="submit" value="Add New baker">

</form>
