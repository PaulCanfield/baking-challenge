<div class="card">
    <h3><a href="<?= $season->path() ?>">Season {{ $season->year }}: {{ $season->title }}</a></h3>
    <p>{{ \Illuminate\Support\Str::words($season->note, 10) }}</p>
</div>
