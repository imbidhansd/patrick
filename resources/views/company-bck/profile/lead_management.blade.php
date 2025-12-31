<?php
    $admin_page_title = 'Lead Management';
?>
@extends('company.layout')


@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="row">

    <div class="col-sm-9">
        <div class="row">
            <div class="col-sm-12">
                <p>This is the designated email address to which inquiry's or requests for your services are sent. To
                    add, change or remove an email address, please contact us at 720-445-4400 or email us at
                    previewtrial@trustpatrick.com.</p>

                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title text-white mb-0">Estimate Request Notifications:</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-left">
                            <p class="text-muted font-13">All requests/inquiries are sent to the following email
                                addresses</p>

                            <h4>Leads Destination Email:</h4> <span>{{ $company_detail->email }}</span>

                            <h4>Additional Notifications:</h4> <span>None Assigned</span>
                        </div>
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
@endsection
