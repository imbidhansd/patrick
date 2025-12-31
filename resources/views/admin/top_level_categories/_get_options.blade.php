@if (!is_null($options))
    @foreach ($options as $option)
        <option value="{{ $option->id }}">{{ $option->title }}</option>
    @endforeach
@endif
