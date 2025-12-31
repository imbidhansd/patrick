<option value="">All</option>
@if (isset($service_categories) && count($service_categories) > 0)
@foreach ($service_categories AS $key => $service_category_item)
<option value="{{ $key }}">{{ $service_category_item }}</option>
@endforeach
@endif