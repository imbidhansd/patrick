@if (isset($service_category_arr) && count($service_category_arr) > 0)

@foreach ($service_category_arr as $type_item)
<h4>{{ $type_item['service_category_type_title'] }}</h4>
@forelse ($type_item['main_category'] as $main_category_item)

<ul>
    <li>
        <div class="checkbox checkbox-primary">
            <input value="main_category_id[]" value="{{ $main_category_item['main_category_id'] }}"
                id="step_4_main_category_{{ $type_item['service_category_type_id'] }}_{{ $main_category_item['main_category_id'] }}"
                type="checkbox" checked class="chk_all">
            <label
                for="step_4_main_category_{{ $type_item['service_category_type_id'] }}_{{ $main_category_item['main_category_id'] }}">
                {{ $main_category_item['main_category_title'] }}
            </label>
            <h6>Service Categories</h6>
            <ul>
                @forelse ($main_category_item['service_categories'] as $item)
                <li>
                    <div class="checkbox checkbox-primary">
                        <input name="service_category_ids[]" value="{{ $item->id }}"
                            id="step_4_service_category_{{ $item->id }}" type="checkbox"
                            class="chk_service_category_id_step_4" checked>
                        <label for="step_4_service_category_{{ $item->id }}">
                            {{ $item->title }}
                        </label>
                    </div>
                </li>
                @empty

                @endforelse
            </ul>

        </div>
    </li>
</ul>

@empty
@endforelse

@endforeach

@endif
