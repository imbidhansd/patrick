<option value="">All</option>
@if (isset($main_categories) && count($main_categories) > 0)
@foreach ($main_categories AS $key => $main_category_item)
<option value="{{ $key }}">{{ $main_category_item }}</option>
@endforeach
@endif