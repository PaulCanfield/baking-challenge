@csrf

<div class="field mb-2">
    <label for="title" class="label font-bold">Title</label>

    <div class="control">

        <input type="text"
               class="input w-full shadow border-2"
               name="title"
               placeholder="Title"
               required
               value="{{ $season->title }}">

    </div>
</div>


<div class="field mb-2">
    <label for="season" class="label font-bold">Year</label>

    <div class="control">

        <input type="text"
               class="input w-full shadow border-2"
               name="year"
               placeholder="Year"
               required
               value="{{ $season->year }}">
    </div>
</div>

<div class="field mb-2">
    <label for="note" class="label font-bold">Note</label>

    <div class="control">
        <textarea class="w-full shadow border-2"
                  style="min-height: 200px"
                  placeholder="General Notes..."
                  name="note" >{{ $season->note }}</textarea>
    </div>
</div>

<div class="field text-right">
    <button type="submit" class="button is-link">{{ $buttonText }}</button>
    <a class="button" href="/season">cancel</a>
</div>

@include('errors')
