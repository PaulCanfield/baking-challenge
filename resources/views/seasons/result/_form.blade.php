@csrf

<div class="field mb-2">
    <label for="title" class="label font-bold">Result</label>

    <div class="control">
        <input type="text"
               class="input w-full shadow border-2"
               name="result"
               placeholder="Result..."
               required
               value="{{ $result->result }}">
    </div>
</div>

<div class="field mb-2">
    <label for="eliminated" class="label font-bold">Eliminates Baker</label>
    <div class="control">
        <input type="checkbox" name="eliminated" value="1">
    </div>
</div>

<div class="field text-right">
    <button type="submit" class="button is-link">{{ $buttonText }}</button>
    <a class="button" href="{{ $requestReferrer }}">cancel</a>
</div>

@include('errors', ['bag' => 'result'])