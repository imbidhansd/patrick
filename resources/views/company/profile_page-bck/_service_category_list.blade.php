@php
$service_category_type_arr = array_keys($company_service_category_list);

$service_category_type_details = \App\Models\ServiceCategoryType::whereIn('id', $service_category_type_arr)->active()->get();
@endphp

@php $offset = ""; @endphp
@foreach ($service_category_type_details AS $service_category_type_item)
@if ($loop->first && $service_category_type_item->id == '2')
@php $offset = "offset-md-6"; @endphp
@endif
<div class="col-md-6 {{ $offset }}">
    <div class="card no-border">
        <div class="card-header bg-header">
            <h3 class="card-title text-white mb-0">{{ $service_category_type_item->title }}</h3>
        </div>
        <div class="card-body">
            <div class="text-left service_category_list">
                @if (count($company_service_category_list[$service_category_type_item->id]) > 0)

                @php
                $main_category_arr = array_keys ($company_service_category_list[$service_category_type_item->id]);

                $main_category_details = \App\Models\MainCategory::whereIn('id', $main_category_arr)->active()->get();
                @endphp

                <ul class="dd-list">
                    @foreach ($main_category_details AS $main_category_item)

                    <li class="dd-item">
                        <div class="dd-handle text-theme_color font-weight-bold cat_name">{{ $main_category_item->title }}</div>
                        @php

                        $category_id_arr =
                        $company_service_category_list[$service_category_type_item->id][$main_category_item->id];
                        $category_details = \App\Models\ServiceCategory::whereIn('id', $category_id_arr)->active()->get();
                        @endphp

                        @if (count($category_details) > 0)
                        <ul class="dd-list">
                            @foreach ($category_details AS $category_item)
                            <li class="dd-item">
                                <div class="dd-handle cat_name">{{ $category_item->title }}</div>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </li>

                    @endforeach
                </ul>
                @endif
            </div>
        </div>
    </div>
</div>

@endforeach
