<?php
    $admin_page_title = 'Contact Us';
?>
@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="row">
    <div class="col-lg-9 col-md-8 col-sm-12">

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h3 class="card-title text-white mb-0">Contact Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-left">
                            @if (isset($global_settings["office_address"]) && !is_null($global_settings["office_address"]))
                                {!! $global_settings["office_address"] !!}
                            @endif
                       

                            @if (isset($company_item) && $company_item->sales_representative_id > 0)
                            <h6>Account Representative</h6>
                            <i class="fas fa-user"></i> {{ $company_item->sales_representative->first_name }} {{ $company_item->sales_representative->last_name }}
                            <br/>
                            <i class="fas fa-at"></i> <a href="mailto: {{ $company_item->sales_representative->email }}">{{ $company_item->sales_representative->email }}</a>
                            @if ($company_item->sales_representative->phone != '')
                            <br/>
                            <i class="fas fa-mobile"></i> <a href="mailto: {{ $company_item->sales_representative->phone }}">{{ $company_item->sales_representative->phone }}</a>
                            @endif
                            @endif
                            
                            <div class="clearfix">&nbsp;</div>
                            <a href="javascript:;" data-toggle="modal" data-target="#emailUsYourQuestion" class="btn btn-success waves-effect waves-light mt-2">Email us your question</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('company.profile._company_profile_sidebar')
</div>


@include('company.pages._submit_faq_modal')
@endsection

@section ('page_js')
@include('company.profile._js')
@stack('_submit_faq_js')
@endsection
