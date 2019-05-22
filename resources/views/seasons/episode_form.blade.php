<form class="w-full text-sm text-right" method="POST" action="{{ $season->path().'/episode' }}">
    @csrf
    <input class="w-full border-4 mb-2" type="text" placeholder="Episode title..." name="title">
    <input class="button-sm" type="submit" value="Add New Episode">
</form>

@include('errors', [ 'bag' => 'episode'] )
