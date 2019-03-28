@if ($errors->{ $bag ?? 'default' }->any())

    <ul class="field list-reset text-center">
        @foreach($errors->{ $bag ?? 'default' }->all() as $error)
            <li class="text-sm text-red w-full">{{ $error }}</li>
        @endforeach
    </ul>

@endif
