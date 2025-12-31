@if (isset($service_category_arr) && count($service_category_arr) > 0)
<div class="row">
    @foreach ($service_category_arr as $type_item)
    <div class="col-md-6">
        <h4>{{ $type_item['service_category_type_title'] }}</h4>
        @foreach ($type_item['main_category'] as $main_category_item)
        @if (isset($package_membership_type))
        @php
        $main_category_fee = \App\Models\MainCategory::active()->find($main_category_item['main_category_id']);
        @endphp
        @endif
        <div class="card">
            <div class="table-responsive111">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>
                                <div class="checkbox checkbox-primary">
                                    <input value="main_category_id[]" value="{{ $main_category_item['main_category_id'] }}" id="step_4_main_category_{{ $type_item['service_category_type_id'] }}_{{ $main_category_item['main_category_id'] }}" type="checkbox" class="chk_all last_input" />
                                    <label for="step_4_main_category_{{ $type_item['service_category_type_id'] }}_{{ $main_category_item['main_category_id'] }}">
                                        {{ $main_category_item['main_category_title'] }}
                                    </label>
                                </div>
                            </th>
                            <th>
                                @if (isset($package_membership_type) && ($package_membership_type == '4' || $package_membership_type == '5'))

                                @php
                                $m_fee = 0;
                                if(isset($package_id) && $package_id != ''){
                                $package_service_category_fee = \App\Models\PackageServiceCategory::where([
                                ['service_category_type_id', $type_item['service_category_type_id']],
                                ['main_category_id', $main_category_item['main_category_id']],
                                ['package_id', $package_id]
                                ])->first();
                                }

                                if (isset($package_service_category_fee) && !is_null($package_service_category_fee)){
                                $m_fee = $package_service_category_fee->fee;
                                } else if ($package_membership_type == '4'){
                                $m_fee = $main_category_fee->annual_price;
                                } else if ($package_membership_type == '5'){
                                $m_fee = $main_category_fee->monthly_price;
                                }
                                @endphp

                                <?php /* @php $m_fee = 0; @endphp
                                  @if ($package_membership_type == '4')
                                  @php $m_fee = $main_category_fee->annual_price; @endphp
                                  @elseif ($package_membership_type == '5')
                                  @php $m_fee = $main_category_fee->monthly_price; @endphp
                                  @endif */ ?>

                                <div class="form-group mb-0">
                                    <input type="text" name="main_service_category_fee[{{ $type_item['service_category_type_id'] }}][{{ $main_category_item['main_category_id'] }}]" value="" data-val="{{ number_format($m_fee, 2, '.', '') }}" class="form-control main_category_fee text-right" disabled />
                                </div>
                                @else
                                <div class="form-group mb-0">
                                    <input type="text" name="ppl_service_category_fee[{{ $type_item['service_category_type_id'] }}][{{ $main_category_item['main_category_id'] }}]" value="" class="form-control ppl_category_fee text-right" disabled />
                                </div>
                                @endif
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2">Service Categories</th>
                        </tr>

                        @foreach ($main_category_item['service_categories'] as $item)
                        <tr class="service_category_item_list">
                            @php $fee = 0; @endphp
                            @if (isset($package_membership_type) && $package_membership_type == '6')
                            @php
                            if(isset($package_id) && $package_id != ''){
                            $package_service_category_fee = \App\Models\PackageServiceCategory::where([['service_category_id', $item->id], ['package_id', $package_id]])->first();
                            }

                            $service_category_fee = \App\Models\ServiceCategory::active()->find($item->id);

                            if (isset($package_service_category_fee) && !is_null($package_service_category_fee)){
                            $fee = $package_service_category_fee->fee;
                            }else if (!is_null($service_category_fee->ppl_price)){
                            $fee = $service_category_fee->ppl_price;
                            } else {
                            $fee = $main_category_fee->ppl_price;
                            }
                            @endphp
                            @endif

                            <td>
                                <div class="checkbox checkbox-primary">
                                    <input name="service_category_ids[]" value="{{ $item->id }}" id="step_4_service_category_{{ $item->id }}" type="checkbox" class="chk_service_category_id_step_4 last_input" checked>
                                    <label for="step_4_service_category_{{ $item->id }}">
                                        {{ $item->title }}
                                    </label>
                                </div>
                            </td>

                            <td>
                                @if (isset($package_membership_type) && $package_membership_type == '6')
                                <div class="form-group mb-0">
                                    <input type="text" name="service_category_fee[{{ $type_item['service_category_type_id'] }}][{{ $item->id }}]" value="" data-val="{{ number_format($fee, 2, '.', '') }}" class="form-control service_category_fee text-right" disabled />
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </thead>
                </table>
            </div>
        </div>
        @endforeach
    </div>
    @endforeach
</div>
@endif
