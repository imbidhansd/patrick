<?php
    $admin_page_title = 'Service Categories';
?>
@extends('company.layout')


@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="row">

    <div class="col-lg-9 col-md-8 col-sm-12">
        @include('admin.companies._service_category_list')
    </div>

    @include('company.profile._company_profile_sidebar')
</div>


{!! Form::open(['url' => url('update-service-category'), 'id' => 'udpate_service_category_form']) !!}

{!! Form::hidden('company_id', $company_item->id) !!}
{!! Form::hidden('item_id', 0, ['id' => 'item_id']) !!}
{!! Form::hidden('item_type', 0, ['id' => 'item_type']) !!}
{!! Form::hidden('item_category_type', 0, ['id' => 'item_category_type']) !!}
{!! Form::hidden('item_process', 0, ['id' => 'item_process']) !!}
{!! Form::close() !!}


@endsection

@section ('page_js')
@include('company.profile._js')
@stack('_edit_company_profile_js')
@endsection
