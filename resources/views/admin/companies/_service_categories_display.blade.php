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
    <div class="card">
        <div class="card-header {{ (isset($removed)) ? 'bg-danger' : 'bg-secondary' }}">
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
                        <div class="dd-handle cat_name">
                            {{ $main_category_item->title }}

                            <div class="float-right cat_buttons">
                                @if (isset($removed))
                                <a href="javascript:;" data-id="{{ $main_category_item->id }}"
                                    data-category_type="{{ $service_category_type_item->id }}" data-type="main_category"
                                    class="update_item add_item" title="Add Item">
                                    <i class="fas fa-redo"></i>
                                </a> &nbsp;

                                <a href="javascript:;" data-id="{{ $main_category_item->id }}"
                                    data-category_type="{{ $service_category_type_item->id }}" data-type="main_category"
                                    class="update_item delete_item text-danger" title="Permanently Delete Item">
                                    <i class="far fa-window-close"></i>
                                </a>
                                @else
                                <a href="javascript:;" data-id="{{ $main_category_item->id }}"
                                    data-category_type="{{ $service_category_type_item->id }}" data-type="main_category"
                                    class="update_item remove_item text-danger" title="Remove Item">
                                    <i class="far fa-window-close"></i>
                                </a>
                                @endif
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
                                <div class="dd-handle cat_name" id="{{ $category_item->id }}">
                                    {{ $category_item->title }}
                                    
                                    @if ($company_item->membership_level->charge_type == 'ppl_price')
                                        @php
                                            $company_service_categories = \App\Models\CompanyServiceCategory::where([
                                                ['company_id', $company_item->id],
                                                ['service_category_id', $category_item->id]
                                            ])
                                            ->active()
                                            ->first();
                                        @endphp

                                        <span class="font-bold text-brown">
                                        @if (!is_null($company_service_categories) && !is_null($company_service_categories->fee))
                                        - $<span class="service_category_fee">{{ number_format($company_service_categories->fee, 2) }}</span>
                                        @else
                                        - $<span class="service_category_fee">0.00</span>
                                        @endif
                                        </span>
                                        
                                        @if (isset($admin_form) && $admin_form)
                                        &nbsp;
                                        <a href="javascript:;" class="btn btn-xs btn-info service_category_price_update_btn" title="Update Price" data-toggle="modal" data-target="#updateServicePriceModal" data-service_category_id="{{ $category_item->id }}"><i class="fas fa-pencil-alt"></i></a>
                                        @endif
                                    @endif

                                    <div class="float-right cat_buttons">
                                        @if (isset($removed))
                                        <a href="javascript:;" data-id="{{ $category_item->id }}"
                                            data-category_type="{{ $service_category_type_item->id }}"
                                            data-type="service_category" class="update_item add_item" title="Add Item">
                                            <i class="fas fa-redo"></i>
                                        </a> &nbsp;

                                        <a href="javascript:;" data-id="{{ $category_item->id }}"
                                            data-category_type="{{ $service_category_type_item->id }}"
                                            data-type="service_category" class="update_item delete_item text-danger"
                                            title="Permanently Delete Item">
                                            <i class="far fa-window-close"></i>
                                        </a>
                                        @else
                                        <a href="javascript:;" data-id="{{ $category_item->id }}"
                                            data-category_type="{{ $service_category_type_item->id }}"
                                            data-type="service_category" class="update_item remove_item text-danger"
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


<div class="modal fade" id="updateServicePriceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">
                    Update Price
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('admin/companies/update-service-category-price'), 'class' => 'module_form', 'id' => 'update_service_category_price_form']) !!}
            {!! Form::hidden('company_id', $company_item->id, ['required' => true]) !!}
            {!! Form::hidden('service_category_id', null, ['id' => 'service_category_id', 'required' => true]) !!}
            
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('Service Category Price') !!}
                    {!! Form::text('fee', null, ['class' => 'form-control', 'id' => 'fee', 'placeholder' => 'Price', 'required' => false]) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light" id="submit_btn">Submit</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>