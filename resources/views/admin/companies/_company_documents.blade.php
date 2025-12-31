@php
$company_licensing = $company_item->company_licensing;
$company_approval_status = $company_item->company_approval_status;
$company_customer_references = $company_item->company_customer_references;
$company_insurances = $company_item->company_insurance;
$company_information = $company_item->company_information;
@endphp

<div id="company_document_update">
    <div class="row">
        @include('admin.companies.documents.__company_application')
        @include('admin.companies.documents.__company_credit_report')
        @include('admin.companies.documents.__company_online_reputation_report')
        @for($bg_check=1;$bg_check<=$company_item->number_of_owners;$bg_check++)
        @include('admin.companies.documents.__company_owner_bg_check_report')
        @endfor
        @include('admin.companies.documents.__company_pre_screen_report')
        @include('admin.companies.documents.__company_state_business_registration')
        @include('admin.companies.documents.__company_proof_of_ownership')
        @include('admin.companies.documents.__company_income_tax_filling')
        @include('admin.companies.documents.__company_state_licensed')
        @include('admin.companies.documents.__company_country_licensed')
        @include('admin.companies.documents.__company_city_licensed')
        @include('admin.companies.documents.__company_written_warrenty')
        @include('admin.companies.documents.__company_subcontractor_agreement')
        @include('admin.companies.documents.__company_customer_references')

        <?php /* 12-3-2020 Added start */ ?>
        @include('admin.companies.documents.__company_general_liablity_insurance')
        @include('admin.companies.documents.__company_worker_comsensation_insurance')
        <?php /* 12-3-2020 Added end */ ?>
        <?php /* @include('admin.companies.documents.__company_insurances') */ ?>
    </div>
</div>


<div class="modal fade" id="CompanyFilesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">
                    Upload <span class="modal-title-text"></span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('admin/companies/upload-company-documents'), 'class' => 'module_form', 'files' => true, 'id' => 'company_document_upload_form']) !!}
            {!! Form::hidden('document_type', null, ['id' => 'document_type', 'required' => true]) !!}
            {!! Form::hidden('field_name', null, ['id' => 'field_name', 'required' => true]) !!}
            {!! Form::hidden('expiry_type', null, ['id' => 'expiry_type', 'required' => true]) !!}
            {!! Form::hidden('company_id', $company_item->id, ['required' => true]) !!}
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('File') !!}
                    {!! Form::file('media', ['class' => 'filestyle', 'accept' => 'image/*,.doc,.docx,.pdf', 'required' => true]) !!}
                </div>

                <div class="form-group" id="expiry_date_field" style="display: none;">
                    {!! Form::label('Expiry Date') !!}
                    {!! Form::text('expiration_date', null, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'MM/DD/YYYY', 'data-toggle' => 'input-mask', 'data-mask-format' => '00/00/0000', 'required' => false]) !!}
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light" id="company_document_upload_btn">Submit</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@include('admin.companies._company_document_modals')


@push('_edit_company_profile_js')
@include('admin.companies._company_document_approval_request_js')
@endpush