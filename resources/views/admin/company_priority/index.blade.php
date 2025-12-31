@extends('admin.layout')
@section('title', $admin_page_title)

@section('content')
@include('admin.includes.breadcrumb')
@include('flash::message')

<div class="card-box">
    {!! Form::open(['class' => 'module_form']) !!}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Trade') !!}
                {!! Form::select('trade_id', $trades, ((isset($selected) && !is_null($selected['trade_id'])) ? $selected['trade_id'] : null), ['class' => 'form-control custom-select', 'id' => 'trade_id', 'placeholder' => 'All', 'required' => true]) !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Top Level Category') !!}
                {!! Form::select('top_level_category_id', $top_level_categories, ((isset($selected) && !is_null($selected['top_level_category_id'])) ? $selected['top_level_category_id'] : null), ['class' => 'form-control custom-select', 'id' => 'top_level_category_id', 'placeholder' => 'All', 'required' => true]) !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Main Category') !!}
                {!! Form::select('main_category_id', $main_categories, ((isset($selected) && !is_null($selected['main_category_id'])) ? $selected['main_category_id'] : null), ['class' => 'form-control custom-select', 'id' => 'main_category_id', 'placeholder' => 'All', 'required' => true]) !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Service Category') !!}
                {!! Form::select('service_category_id', $service_categories, ((isset($selected) && !is_null($selected['service_category_id'])) ? $selected['service_category_id'] : null), ['class' => 'form-control custom-select', 'id' => 'service_category_id', 'placeholder' => 'All', 'required' => false]) !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Zipcode') !!}
                {!! Form::text('zipcode', ((isset($selected) && !is_null($selected['zipcode'])) ? $selected['zipcode'] : null), ['class' => 'form-control', 'placeholder' => 'Zipcode', 'data-toggle'
                => 'input-mask', 'data-mask-format' => '00000', 'required' => true]) !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Mile Range') !!}
                {!! Form::select('mile_range', config('config.mile_options'), ((isset($selected) && !is_null($selected['mile_range'])) ? $selected['mile_range'] : null), ['class' => 'form-control custom-select', 'placeholder' => 'Mile Range', 'required' => false]) !!}
            </div>
        </div>
    </div>

    <hr />
    <button type="submit" class="btn btn-info float-right waves-effect waves-light">Search</button>
    {!! Form::close() !!}
</div>


<div class="card-box">
    <h3>Search Result</h3>
    @if (isset($company_list) && count($company_list) > 0)
    <div class="row">
        @php
        $service_category_type_arr = array_keys($company_list);

        $service_category_type_details = \App\Models\ServiceCategoryType::whereIn('id', $service_category_type_arr)->active()->pluck('title', 'id');

        @endphp

        @foreach ($service_category_type_details AS $service_category_type_id => $service_category_type_item)
        <div class="col-md-6">
            <div class="card mb-0">
                <div class="card-header bg-primary">
                    <h3 class="card-title text-white mb-0">{{ $service_category_type_item }}</h3>
                </div>
                <div class="card-body">
                    <div class="text-left service_category_list">
                        @if (count($company_list[$service_category_type_id]) > 0)

                        @php
                        $main_category_arr = array_keys ($company_list[$service_category_type_id]);

                        $main_category_details = \App\Models\MainCategory::whereIn('id', $main_category_arr)->active()->pluck('title', 'id');
                        @endphp

                        <ul class="dd-list p-0">
                            @foreach ($main_category_details AS $main_category_id => $main_category_item)

                            <li class="dd-item">
                                <div class="dd-handle main_category">
                                    {{ $main_category_item }}
                                </div>

                                @php

                                $category_id_arr =
                                array_keys ($company_list[$service_category_type_id][$main_category_id]);

                                $category_details = \App\Models\ServiceCategory::whereIn('id', $category_id_arr)->active()->pluck('title', 'id');
                                @endphp

                                @if (count($category_details) > 0)
                                <ul class="dd-list">
                                    @foreach ($category_details AS $category_id => $category_item)

                                    <li class="dd-item">
                                        <div class="dd-handle service_category">
                                            {{ $category_item }}
                                        </div>

                                        @php
                                        $zipcode_list = $company_list[$service_category_type_id][$main_category_id][$category_id];
                                        @endphp

                                        @if (count($zipcode_list) > 0)
                                        <ul class="dd-list">
                                            @foreach($zipcode_list AS $zipcode_key => $zipcode_item)
                                            <li class="dd-item">
                                                <div class="dd-handle zipcode">
                                                    {{ $zipcode_key }}
                                                </div>

                                                @if (count($zipcode_item) > 0)
                                                <ul class="dd-list">
                                                    @foreach ($zipcode_item AS $key => $company_name_item)
                                                    <li class="dd-item">
                                                        <div class="dd-handle">
                                                            Company Name: {{ $company_name_item }}
                                                            &nbsp; (Priority: {{ $key+1 }})
                                                        </div>  
                                                    </li>
                                                    @endforeach
                                                </ul>
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                        @endforeach
                                    </li>
                                </ul>
                                @endif

                                @endforeach
                            </li>
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @endforeach
    </div>
    @else
    <span class="text-danger">No companies found.</span>
    @endif
</div>
@stop


@section('page_js')
<!-- Plugins js -->
<script src="{{ asset('/themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/js/pages/form-masks.init.js') }}"></script>

<!-- Plugins css -->
<link href="{{ asset('/themes/admin/assets/libs/nestable2/jquery.nestable.min.css') }}" rel="stylesheet"
    type="text/css" />
<!-- Plugins js-->
<script type="text/javascript" src="{{ asset('/themes/admin/assets/libs/nestable2/jquery.nestable.min.js') }}"></script>

@include('admin.broadcast_emails._js')
@stop