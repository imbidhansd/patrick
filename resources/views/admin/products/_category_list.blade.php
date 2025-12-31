@if (isset($main_categories) && count($main_categories) > 0)
	@php
        $main_category_arr = array_chunk($main_categories->toArray(), ceil(count($main_categories) / 3));
    @endphp
    
    <div class="row">
        @foreach ($main_category_arr as $arr_item)
        <div class="col-md-4">
            @foreach ($arr_item as $item)
            <div class="checkbox checkbox-primary">
                <input name="main_category_id[]" class="chk_main_category_id"
                    data-text="{{ $item['main_category']['title'] }}" id="main_category_{{ $item['main_category']['id'] }}" value="{{ $item['main_category']['id'] }}"
                    type="checkbox" />
                <label for="main_category_{{ $item['main_category']['id'] }}">
                    {{ $item['main_category']['title'] }}
                </label>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>

@elseif (isset($service_categories) && count($service_categories) > 0)
    @php
        $service_category_arr = array_chunk($service_categories->toArray(), ceil(count($service_categories) / 3));
    @endphp
    
    <div class="row">
        @foreach ($service_category_arr as $arr_item)
        <div class="col-md-4">
            @foreach ($arr_item as $item)
            <div class="checkbox checkbox-primary">
                <input name="service_category_id[]" class="chk_service_category_id"
                    data-text="{{ $item['title'] }}" id="service_category_{{ $item['id'] }}" value="{{ $item['id'] }}"
                    type="checkbox" />
                <label for="service_category_{{ $item['id'] }}">
                    {{ $item['title'] }}
                </label>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
@endif