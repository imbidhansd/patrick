@if (isset($service_category) && count($service_category) > 0)
	<option value="">Select Service Category</option>
	@foreach ($service_category AS $key => $category_item)
	<option value="{{ $key }}">{{ $category_item }}</option>
	@endforeach
@endif