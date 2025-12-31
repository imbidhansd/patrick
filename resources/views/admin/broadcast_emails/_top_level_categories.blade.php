<option value="">All</option>
@if (isset($top_level_categories) && count($top_level_categories) > 0)
@foreach ($top_level_categories AS $key => $top_level_category_item)
<option value="{{ $key }}">{{ $top_level_category_item }}</option>
@endforeach
@endif