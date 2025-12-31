@if (isset($service_categories) && count($service_categories) > 0)
<option value="">Select Service Category</option>
@foreach ($service_categories AS $key => $service_category_item)
<option value="{{ $key }}">{{ $service_category_item }}</option>
@endforeach
@endif