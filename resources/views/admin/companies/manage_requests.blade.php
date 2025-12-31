@extends('admin.layout')
@section('title', $admin_page_title)

@section ('content')

@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => $module_urls['list'],
$admin_page_title => '']])
@include('flash::message')

<div class="card-box">
    @include('admin.includes.formErrors')

    @php
    $company_licensing = $company_item->company_licensing;
    $company_customer_references = $company_item->company_customer_references;
    $company_approval_status = $company_item->company_approval_status;
    $company_users = $company_item->company_users_approval_remaining;
    @endphp

    <div class="row">
        @include('admin.companies.company_requests._company_bio')
        @include('admin.companies.company_requests._company_users')
        @include('admin.companies.company_requests._company_logo')
        @include('admin.companies.company_requests._company_state_business_registration')
        @include('admin.companies.company_requests._company_proof_of_ownership')
        @include('admin.companies.company_requests._company_state_licensed')
        @include('admin.companies.company_requests._company_country_licensed')
        @include('admin.companies.company_requests._company_city_licensed')
        @include('admin.companies.company_requests._company_work_agreement_warranty')
        @include('admin.companies.company_requests._company_subcontractor_agreement')
        @include('admin.companies.company_requests._company_customer_references')

        <?php /* @if (is_null($company_approval_status) || (
            !is_null($company_approval_status) &&
            $company_approval_status->company_bio != 'in process' &&
            $company_approval_status->registered_legally_to_state != 'in process' &&
            $company_approval_status->proof_of_ownership != 'in process' &&
            $company_approval_status->state_licensing != 'in process' &&
            $company_approval_status->country_licensing != 'in process' &&
            $company_approval_status->city_licensing != 'in process' &&
            $company_approval_status->work_agreements_warranty != 'in process'
        ))
        <p class="mb-0">No Request available.</p>
        @endif */ ?>
    </div>
</div>


@include('admin.companies._company_document_modals')

<div class="modal fade" id="rejectCompanyInfoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => '', 'class' => 'module_form', 'id' => 'company_bio_reject_form']) !!}
            {!! Form::hidden('approval_status', 'pending') !!}
            {!! Form::hidden('approval_status_type', null, ['id' => 'approval_status_type']) !!}
            {!! Form::hidden('company_id', $company_item->id) !!}

            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('Note') !!}
                    {!! Form::textarea('reject_note', null, ['class' => 'form-control', 'required' => true]) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop

@section('page_js')
@include('admin.companies._company_document_approval_request_js')
@include('admin.companies._company_logo_bio_request_js')
@stack('manage_request_js')
@stop