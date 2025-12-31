@if (isset($service_category_arr) && count($service_category_arr) > 0)
<div class="row">
    @foreach ($service_category_arr as $type_item)

    <div class="col-md-6 col-sm-12">
        <h4>{{ $type_item['service_category_type_title'] }}</h4>
        @if (!is_null($type_item['main_category']))
        @foreach ($type_item['main_category'] as $main_category_item)
        <div class="checkbox checkbox-primary chk_main_cat">
            <input value="main_category_id[]" value="{{ $main_category_item['main_category_id'] }}"
                id="step_2_main_category_{{ $type_item['service_category_type_id'] }}_{{ $main_category_item['main_category_id'] }}"
                type="checkbox" class="main_category_item chk_all last_input" checked>
            <label
                for="step_2_main_category_{{ $type_item['service_category_type_id'] }}_{{ $main_category_item['main_category_id'] }}">
                {{ $main_category_item['main_category_title'] }}
            </label>

            <h6><strong>Service Categories</strong></h6>
            <div class="service_category_item_list pt-3 pb-3" style="background: #fafafa; border: 1px solid #ddd;">
                @forelse ($main_category_item['service_categories'] as $item)

                <div class="checkbox checkbox-primary">
                    <?php
                    $checked_var = '';
                    if (isset($selected_service_category_ids) && is_array($selected_service_category_ids) && count($selected_service_category_ids) > 0) {
                        if (in_array($item->id, $selected_service_category_ids)) {
                            $checked_var = 'checked';
                        }
                    } else {
                        $checked_var = 'checked';
                    }
                    ?>

                    <input name="service_category_ids[]" value="{{ $item->id }}"
                        id="step_2_service_category_{{ $item->id }}" type="checkbox"
                        class="chk_service_category_id_step_2 last_input chk_service_cat" {{ $checked_var }}
                        data-parsley-errors-container="#{{ $step_no }}-service-category-error-container"
                        data-parsley-required-message="Kindly select atlest one category" required />

                    <label for="step_2_service_category_{{ $item->id }}">
                        {{ $item->title }}
                    </label>
                </div>

                @empty

                @endforelse
            </div>
        </div>
        @endforeach
        @endif
    </div>

    @if ($loop->iteration % 2 == 0)
</div>
<div class="row">
    @endif

    @endforeach
</div>
<div id="{{ $step_no }}-service-category-error-container"></div> <div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>
@endif
