@if (isset($service_category_arr) && count($service_category_arr) > 0)

@foreach ($service_category_arr as $type_item)
<h4>{{ $type_item['service_category_type_title'] }}</h4>
@forelse ($type_item['main_category'] as $main_category_item)


<div class="checkbox checkbox-primary">
    <input value="main_category_id[]" value="{{ $main_category_item['main_category_id'] }}"
        id="step_4_main_category_{{ $type_item['service_category_type_id'] }}_{{ $main_category_item['main_category_id'] }}"
        type="checkbox" checked class="chk_all last_input">
    <label
        for="step_4_main_category_{{ $type_item['service_category_type_id'] }}_{{ $main_category_item['main_category_id'] }}">
        {{ $main_category_item['main_category_title'] }}
    </label>
    <h6><strong>Service Categories</strong></h6>
    <div class="service_category_item_list">
        @forelse ($main_category_item['service_categories'] as $item)

        <div class="checkbox checkbox-primary">
            <input name="service_category_ids[]" value="{{ $item->id }}" id="step_4_service_category_{{ $item->id }}"
                type="checkbox" class="chk_service_category_id_step_4 last_input" checked>
            <label for="step_4_service_category_{{ $item->id }}">
                {{ $item->title }}
            </label>
        </div>

        @empty

        @endforelse


    </div>
</div>


@empty
@endforelse

@endforeach

@endif
