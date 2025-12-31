@if (isset($top_level_categories) && count($top_level_categories) > 0)
<option value="">Select Service Category</option>
@foreach ($top_level_categories AS $key => $top_level_category_item)
<option value="{{ $key }}">{{ $top_level_category_item }}</option>
@endforeach
@endif