<option value="">Select</option>
@forelse ($main_categories as $item)
<option value="{{ $item->id }}">{{ $item->title }}</option>
@empty

@endforelse
