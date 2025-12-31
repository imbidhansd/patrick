@if (isset($show_none) && $show_none == true)
<option value="">None</option>
@else
<option value="">Select</option>
@endif
@forelse ($main_categories as $item)
<option value="{{ $item->id }}">{{ $item->title }}</option>
@empty

@endforelse
