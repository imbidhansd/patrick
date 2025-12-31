@if (isset($service_category_arr) && count($service_category_arr) > 0)
	@php
		$service_category_type_arr = array_keys($service_category_arr);

		$service_category_type_details = \App\Models\ServiceCategoryType::whereIn('id', $service_category_type_arr)->active()->get();
	@endphp

<dl>
	@foreach ($service_category_type_details AS $service_category_type_item)
		<dd><span class="category_type bold_font">{{ $service_category_type_item->title }}</span>
        	@if (count($service_category_arr[$service_category_type_item->id]) > 0)
	            @php
	            $main_category_arr = array_keys ($service_category_arr[$service_category_type_item->id]);

	            $main_category_details = \App\Models\MainCategory::whereIn('id', $main_category_arr)->active()->get();
	            @endphp

	            <dl>
                    @foreach ($main_category_details AS $main_category_item)
                    	<dd>&nbsp;&nbsp;<span class="bold_font">{{ $main_category_item->title }}</span>
                    	@php

                        	$category_id_arr = $service_category_arr[$service_category_type_item->id][$main_category_item->id];
                        	$category_details = \App\Models\ServiceCategory::whereIn('id', $category_id_arr)->active()->get();
                        @endphp

                        @if (count($category_details) > 0)
	                        <dl>
	                            @foreach ($category_details AS $category_item)
	                            <dd>&nbsp;&nbsp;&nbsp;&nbsp;{{ $category_item->title }}</dd>
	                            @endforeach
	                        </dl>
                        @endif
                    	</dd>
                    @endforeach
            	</dl>
            @endif
		</dd>
	@endforeach
</dl>
@endif