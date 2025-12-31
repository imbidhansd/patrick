@if (isset($top_level_categories) && count($top_level_categories) > 0)
	<h3>Top Level Categories</h3>
	<div class="row">
		@foreach ($top_level_categories AS $key => $category_item)
		<div class="col-md-4">
	        <div class="radio radio-primary">
	        	<input name="top_level_category_id" value="{{ $key }}" id="top_level_category_{{ $key }}" type="radio" class="top_level_category_id" />
	            <label for="top_level_category_{{ $key }}">
	                {{ $category_item }}
	            </label>
	        </div>
	    </div>
	    @endforeach
    </div>
@endif