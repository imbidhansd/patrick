@php
$service_category_type_arr = array_keys($company_service_category_list);

$service_category_type_details = \App\Models\ServiceCategoryType::whereIn('id', $service_category_type_arr)->active()->get();
@endphp

@foreach ($service_category_type_details AS $service_category_type_item)

<div class="col-sm-6">
    <div class="card">
        <div class="card-header {{ (isset($removed)) ? 'bg-danger' : 'bg-primary' }}">
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
                        <div class="dd-handle">
                            {{ $main_category_item->title }}

                            <div class="float-right">
                                @if (isset($removed))
                                <a href="javascript:;" data-id="{{ $main_category_item->id }}"
                                    data-category_type="{{ $service_category_type_item->id }}" data-type="main_category"
                                    class="update_item add_item" title="Add Item">
                                    <i class="fas fa-redo"></i>
                                </a> &nbsp;

                                <a href="javascript:;" data-id="{{ $main_category_item->id }}"
                                    data-category_type="{{ $service_category_type_item->id }}" data-type="main_category"
                                    class="update_item delete_item" title="Permanently Delete Item">
                                    <i class="far fa-window-close"></i>
                                </a>
                                @else
                                <a href="javascript:;" data-id="{{ $main_category_item->id }}"
                                    data-category_type="{{ $service_category_type_item->id }}" data-type="main_category"
                                    class="update_item remove_item" title="Remove Item">
                                    <i class="far fa-window-close"></i>
                                    @endif
                                </a>
                            </div>
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
                                <div class="dd-handle">
                                    {{ $category_item->title }}

                                    <div class="float-right">
                                        @if (isset($removed))
                                        <a href="javascript:;" data-id="{{ $category_item->id }}"
                                            data-category_type="{{ $service_category_type_item->id }}"
                                            data-type="service_category" class="update_item add_item" title="Add Item">
                                            <i class="fas fa-redo"></i>
                                        </a> &nbsp;

                                        <a href="javascript:;" data-id="{{ $category_item->id }}"
                                            data-category_type="{{ $service_category_type_item->id }}"
                                            data-type="service_category" class="update_item delete_item"
                                            title="Permanently Delete Item">
                                            <i class="far fa-window-close"></i>
                                        </a>
                                        @else
                                        <a href="javascript:;" data-id="{{ $category_item->id }}"
                                            data-category_type="{{ $service_category_type_item->id }}"
                                            data-type="service_category" class="update_item remove_item"
                                            title="Remove Item">
                                            <i class="far fa-window-close"></i>
                                        </a>
                                        @endif


                                    </div>
                                </div>
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
