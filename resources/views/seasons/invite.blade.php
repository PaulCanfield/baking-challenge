<div class="card mb-2">
    <form action="{{ $season->path() }}/invite" class="w-full flex text-sm" method="POST">
        @csrf

        <input type="text" name="email" class="flex-1 mr-2 shadow pl-2" placeholder="Invite Email address...">
        <input type="submit" class="button-sm text-xs" value="Invite">
    </form>

    @include('errors', [ 'bag' => 'invitation' ])
</div>
