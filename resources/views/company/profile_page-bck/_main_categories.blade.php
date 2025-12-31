@if (isset($main_categories) && count($main_categories) > 0)
<option value="">Select Main Category</option>
@foreach ($main_categories AS $key => $main_category_item)
<option value="{{ $key }}">{{ $main_category_item }}</option>
@endforeach
@endif