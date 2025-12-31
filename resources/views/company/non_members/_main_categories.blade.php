@if (isset($main_categories) && count($main_categories) > 0)
@foreach ($main_categories AS $key => $main_category_item)
<option value="{{ $key }}" {{ ((isset($selected_main_categories) && count($selected_main_categories) > 0 && in_array($key, $selected_main_categories)) ? 'selected' : '') }}>{{ $main_category_item }}</option>
@endforeach
@endif