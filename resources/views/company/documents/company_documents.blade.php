<?php
    $admin_page_title = 'Company Documents';
?>
@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="row">
    <div class="col-lg-9 col-md-8 col-sm-12">
        <div class="card-box">
            @php
                $company_licensing = $company_item->company_licensing;
                $company_approval_status = $company_item->company_approval_status;
                $company_customer_references = $company_item->company_customer_references;
                $company_insurances = $company_item->company_insurance;
            @endphp

            <div class="row">
                <?php /* @include('company.documents._company_bio')
                @include('company.documents._company_logo') */ ?>
                
                @include('company.documents._company_application')
                <?php /* @include('company.documents._company_pre_screen_report') */ ?>
                @include('company.documents._company_state_business_registration')
                @include('company.documents._company_proof_of_ownership')
                @include('company.documents._company_income_tax_filling')
                @include('company.documents._company_state_licensed')
                @include('company.documents._company_country_licensed')
                @include('company.documents._company_city_licensed')
                @include('company.documents._company_written_warrenty')
                @include('company.documents._company_subcontractor_agreement')
                @include('company.documents._company_customer_references')
                <?php /* @include('company.documents._company_insurances') */ ?>
            
                @include('company.documents._company_general_liability_insurance')
                @include('company.documents._company_worker_compensation_insurance')
            </div>
        </div>
    </div>
    @include('company.profile._company_profile_sidebar')
</div>
@endsection

@section ('page_js')
@include('company.profile._js')
@endsection