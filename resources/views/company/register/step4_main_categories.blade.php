@if (isset($show_none) && $show_none == true)
<option value="">None</option>
@else
<option value="">Select</option>
@endif
@forelse ($main_categories as $item)
<option
    {{ isset($selected_category_ids) && is_array($selected_category_ids) && in_array($item->id, $selected_category_ids) ? 'selected' : '' }}
    value="{{ $item->id }}">{{ $item->title }}
</option>
@empty

@endforelse
