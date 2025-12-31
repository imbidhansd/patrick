@if (isset($service_category_types) && count($service_category_types) > 0)
	<h3>Service Category Types</h3>
	@foreach ($service_category_types AS $key => $service_category_type_item)
		<div class="form-group">
	        <div class="radio radio-primary">
	        	<input name="service_category_type_id" value="{{ $key }}" id="service_category_types_{{ $key }}" type="radio" class="service_category_type_id" />
	            <label for="service_category_types_{{ $key }}">
	                {{ $service_category_type_item }}
	            </label>
	        </div>
	    </div>
    @endforeach
@endif