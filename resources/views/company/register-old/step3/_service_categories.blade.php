@if (isset($service_category_arr) && count($service_category_arr) > 0)

@foreach ($service_category_arr as $type_item)

<h4>{{ $type_item['service_category_type_title'] }}</h4>

@forelse ($type_item['main_category'] as $main_category_item)
@if (isset($package_membership_type))
@php
$main_category_fee = \App\Models\MainCategory::active()->find($main_category_item['main_category_id']);
@endphp
@endif


<div class="checkbox checkbox-primary">
    <input value="main_category_id[]" class="main_category_item" value="{{ $main_category_item['main_category_id'] }}"
        id="step_2_main_category_{{ $type_item['service_category_type_id'] }}_{{ $main_category_item['main_category_id'] }}"
        type="checkbox" class="chk_all last_input" checked>
    <label for="step_2_main_category_{{ $type_item['service_category_type_id'] }}_{{ $main_category_item['main_category_id'] }}">
        @if (isset($package_membership_type) && ($package_membership_type == '4' || $package_membership_type == '5'))
        <div class="row">
            <div class="col-md-8">
                {{ $main_category_item['main_category_title'] }}
            </div>
            <div class="col-md-4">
                @php $m_fee = 0; @endphp
                @if ($package_membership_type == '4')
                    @php $m_fee = $main_category_fee->annual_price; @endphp
                @elseif ($package_membership_type == '5')
                    @php $m_fee = $main_category_fee->monthly_price; @endphp
                @endif

                <div class="form-group">
                    <input type="text" name="main_service_category_fee[{{ $type_item['service_category_type_id'] }}][{{ $main_category_item['main_category_id'] }}]" value="{{ number_format($m_fee, 2, '.', '') }}" class="form-control main_category_fee" />
                </div>
            </div>
        </div>
        @else
        {{ $main_category_item['main_category_title'] }}
        @endif
    </label>

    <h6><strong>Service Categories</strong></h6>
    <div class="service_category_item_list">

        @forelse ($main_category_item['service_categories'] as $item)

            @php $fee = 0; @endphp
            @if (isset($package_membership_type) && $package_membership_type == '6')
                @php
                    $service_category_fee = \App\Models\ServiceCategory::active()->find($item->id);
                    if (is_null($service_category_fee->ppl_price)){
                        $fee = $main_category_fee->ppl_price;
                    } else {
                        $fee = $service_category_fee->ppl_price;
                    }
                @endphp
            @endif
        
            <div class="checkbox checkbox-primary">

                <?php
                    $checked_var = '';
                    if (isset($selected_service_category_ids) && is_array($selected_service_category_ids) && count($selected_service_category_ids) > 0){
                        if (in_array($item->id, $selected_service_category_ids)){
                            $checked_var = 'checked';
                        }
                    }else{
                        $checked_var = 'checked';
                    }
                ?>

                <input name="service_category_ids[]" value="{{ $item->id }}" id="step_2_service_category_{{ $item->id }}"
                    type="checkbox" class="chk_service_category_id_step_2 last_input" {{ $checked_var }}>
                <label for="step_2_service_category_{{ $item->id }}">
                    @if (isset($package_membership_type) && $package_membership_type == '6')
                    <div class="row">
                        <div class="col-md-8">
                            {{ $item->title }}
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" name="service_category_fee[{{ $type_item['service_category_type_id'] }}][{{ $item->id }}]" value="{{ number_format($fee, 2, '.', '') }}" class="form-control service_category_fee" />
                            </div>
                        </div>
                    </div>
                    @else
                    {{ $item->title }}
                    @endif
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
