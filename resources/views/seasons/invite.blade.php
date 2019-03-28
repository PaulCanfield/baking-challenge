<div class="card mb-2">
    <h3 class="mt-0 pt-0">Invite</h3>
    <form action="{{ $season->path() }}/invite" class="w-full flex text-sm" method="POST">
        @csrf

        <input type="text" name="email" class="flex-1 mr-2 shadow pl-2" placeholder="Email address...">
        <input type="submit" class="button-sm text-xs" value="Invite">
    </form>

    @include('errors', [ 'bag' => 'invitation' ])
</div>
