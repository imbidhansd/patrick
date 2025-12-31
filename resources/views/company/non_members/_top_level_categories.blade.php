@if (isset($top_level_categories) && count($top_level_categories) > 0)
@foreach ($top_level_categories AS $key => $top_level_category_item)
<option value="{{ $key }}" {{ ((isset($selected_top_level_categories) && count($selected_top_level_categories) > 0 && in_array($key, $selected_top_level_categories)) ? 'selected' : '') }}>{{ $top_level_category_item }}</option>
@endforeach
@endif