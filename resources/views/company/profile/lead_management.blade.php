<?php
    $admin_page_title = 'Lead Management';
?>
@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="row">

    <div class="col-lg-9 col-md-8 col-sm-12">
        @include('admin.companies._leads_display')
    </div>

    @include('company.profile._company_profile_sidebar')
</div>
@endsection

@section ('page_js')
@include('company.profile._js')
@stack('_edit_company_profile_js')
@endsection
