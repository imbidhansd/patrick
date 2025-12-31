@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')
@include('flash::message')

<!-- Basic Form Wizard -->
<div class="card-box">
    <div class="row">
        <div class="col-md-2">&nbsp;</div>
        <div class="col-md-8 text-center">
            <h1 class="text-center">{{ $admin_page_title }}</h1>
            <div class="clearfix">&nbsp;</div>

            @include('admin.includes.formErrors')
    

            <?php 
                $company_user = Auth::guard('company_user')->user();
            ?>

            <h3 class="registration_thankyou_color">An invoice has been generated and emailed to {{ $company_user->email }}</h3>
            <div class="clearfix">&nbsp;</div>
            <h4 class="text-danger">Step 2 Submit Your Online Application</h4>
            <div class="clearfix">&nbsp;</div>

            <div class="alert alert-danger">
                Next Step: Submit your online application for approval. Click on the button below.
            </div>
            <div class="clearfix">&nbsp;</div>

            <a href="{{ url('account/application') }}" class="btn btn-primary btn-md">Submit Application</a>
            <div class="clearfix">&nbsp;</div>
            <div class="clearfix">&nbsp;</div>
        </div>
    </div>
</div>
@endsection

@section ('page_js')
@endsection
