@if (isset($company_approval_status) && !is_null($company_approval_status))

<?php //dd($company_approval_status->toArray()) ?>

@php
$company_licensing = $company_item->company_licensing;
$company_insurances = $company_item->company_insurance;
@endphp
            
<?php $company_approval_status_arr = Arr::except($company_approval_status->toArray(), ['id', 'company_id', 'created_at', 'updated_at']); ?>
<div class="card">
    <div class="card-header bg-info">
        <h3 class="card-title text-white mb-0">Application Progress</h3>
    </div>
    <div class="card-body p-0">
        <ul class="list-group bs-ui-list-group">
            @include ('company.profile.approval_status.background_check_fee')
            @include ('company.profile.approval_status.one_time_setup_fee')
            @include ('company.profile.approval_status.background_check_submittal')
            @include ('company.profile.approval_status.background_check_process')
            @include ('company.profile.approval_status.pre_screening_process')
            @include ('company.profile.approval_status.online_application_process')
            @include ('company.profile.approval_status.registered_legally_to_state')
            @include ('company.profile.approval_status.proof_of_ownership')
            @include ('company.profile.approval_status.state_licensing')
            @include ('company.profile.approval_status.country_licensing')
            @include ('company.profile.approval_status.city_licensing')
            @include ('company.profile.approval_status.work_agreements_warranty')
            @include ('company.profile.approval_status.insurance_documents')
            @include ('company.profile.approval_status.customer_references')
            @include ('company.profile.approval_status.company_logo')
            @include ('company.profile.approval_status.company_bio')
        </ul>
    </div>
    <div class="card-footer text-center">

    </div>
</div>

@endif
