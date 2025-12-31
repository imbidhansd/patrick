@php
$service_category_type_arr = array_keys($company_service_category_list);

$service_category_type_details = \App\Models\ServiceCategoryType::whereIn('id', $service_category_type_arr)->active()->get();

$offset = "";
@endphp

@foreach ($service_category_type_details AS $service_category_type_item)
@if ($loop->first && $service_category_type_item->id == '2')
@php $offset = "offset-md-6"; @endphp
@endif

<div class="col-lg-6 {{ $offset }}">
    <ul class="dd-list">
        <li class="dd-item">
            <div class="dd-handle">
                <b class="text-theme">{{ $service_category_type_item->title }}</b>
            </div>
        </li>

        @if (count($company_service_category_list[$service_category_type_item->id]) > 0)

        @php
        $main_category_arr = array_keys ($company_service_category_list[$service_category_type_item->id]);

        $main_category_details = \App\Models\MainCategory::whereIn('id', $main_category_arr)->active()->get();
        @endphp
        <ul class="dd-list">
            @foreach ($main_category_details AS $main_category_item)
            <li class="dd-item">
                <div class="dd-handle">
                    <b class="text-primary">{{ $main_category_item->title }}</b>
                </div>

                @php

                $category_id_arr =
                $company_service_category_list[$service_category_type_item->id][$main_category_item->id];
                $category_details = \App\Models\ServiceCategory::whereIn('id', $category_id_arr)->active()->get();
                @endphp

                @if (count($category_details) > 0)
                <ul class="dd-list">
                    @foreach ($category_details AS $category_item)
                    <li class="dd-item">
                        <div class="dd-handle category_name category_selection" data-service_category_type="{{ $service_category_type_item->id }}" data-top_level_category="{{ $category_item->top_level_category_id }}" data-main_category="{{ $main_category_item->id }}" data-service_category="{{ $category_item->id }}">{{ $category_item->title }}</div>
                    </li>
                    @endforeach
                </ul>
                @endif
            </li>
            @endforeach
        </ul>
        @endif
    </ul>
</div>
@endforeach