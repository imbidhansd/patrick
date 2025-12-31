<?php
    $admin_page_title = 'Contact Us';
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
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title text-white mb-0">Contact Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-left">
                            <p class="text-muted">
                                <strong>TrustPatrick.com</strong>
                            </p>

                            <p class="text-left">
                                <span>3531 S Logan St</span>
                                <br />
                                <span>Suite D212 Englewood, CO 80113</span>
                                <br />
                                <span>Phone – (866) 966-2287</span>
                            </p>
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
