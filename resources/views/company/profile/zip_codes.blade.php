<?php
$admin_page_title = 'Zip Code/Zip Code Radius';
?>
@extends('company.layout')


@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="row">
    <div class="col-lg-9 col-md-8 col-sm-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">

                <div class="card">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title text-white mb-0">Zip and Miles</h3>
                    </div>
                    <div class="card-body">
                        @include('admin.companies._zipcodes')
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('company.profile._company_profile_sidebar')
</div>
@endsection

@section ('page_js')
@include('company.profile._js')
@stack('_edit_company_profile_js')
@endsection
