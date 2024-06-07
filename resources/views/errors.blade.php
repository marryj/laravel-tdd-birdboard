@if ($errors->any())
    <ul class="field mt-6 list-reset">
        @foreach ($errors->all() as $error)
            <li class="text-sm text-red-600">{{ $error }}</li>
        @endforeach
    </ul>
@endif