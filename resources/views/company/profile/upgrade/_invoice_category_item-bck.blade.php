@if (isset($category_listing_data) && count($category_listing_data) > 0)
	<table width="100%" class="table" border="0">
		@php 
			$service_category_type_id = $main_category_id = "";
		@endphp

		@foreach ($category_listing_data AS $i => $category_item)
			
			@if ($service_category_type_id != $category_item->service_category_type_id)
				<tr>
					<th>
						<span style="color: blue">{{ $category_item->service_category_type->title }}</span>
					</th>
				</tr>

				@php
					$service_category_type_id = $category_item->service_category_type_id;
				@endphp
			@endif
			
			
			@if ($main_category_id != $category_item->main_category_id)

				<tr>
					<th>
						{{ $category_item->main_category->title }}
					</th>
				</tr>

				@php
					$main_category_id = $category_item->main_category_id;
				@endphp
			@endif

			<tr>
				<td>
					{{ $category_item->service_category->title }}
				</td>
			</tr>
			
		@endforeach
	
	</table>
@endif