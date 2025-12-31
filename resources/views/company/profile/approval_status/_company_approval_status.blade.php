@if (isset($company_approval_status) && !is_null($company_approval_status))

<?php //dd($company_approval_status->toArray()) ?>

@php
$company_licensing = $company_item->company_licensing;
$company_insurance = $company_item->company_insurance;
$company_information = $company_item->company_information;
@endphp
            
<?php $company_approval_status_arr = Arr::except($company_approval_status->toArray(), ['id', 'company_id', 'created_at', 'updated_at']); ?>
<div class="card">
    <div class="card-header bg-header">
        <h3 class="card-title text-white mb-0">Application Progress</h3>
    </div>
    <div class="card-body p-0">
        <ul class="list-group bs-ui-list-group">
            @include ('company.profile.approval_status.company_logo')
            @include ('company.profile.approval_status.company_bio')
            @include ('company.profile.approval_status.background_check_fee')
            @include ('company.profile.approval_status.one_time_setup_fee')
            @include ('company.profile.approval_status.online_application_process')
            @include ('company.profile.approval_status.background_check_submittal')
            @include ('company.profile.approval_status.background_check_process')
            @include ('company.profile.approval_status.pre_screening_process')
            @include ('company.profile.approval_status.customer_references')
            @include ('company.profile.approval_status.general_liablity_insurance_document')
            
            @if ($company_item->trade_id == 1)
            @include ('company.profile.approval_status.worker_compensation_insurance_document')
            @endif
            
            @include ('company.profile.approval_status.registered_legally_to_state')
            @include ('company.profile.approval_status.proof_of_ownership')
            @include ('company.profile.approval_status.state_licensing')
            @include ('company.profile.approval_status.country_licensing')
            @include ('company.profile.approval_status.city_licensing')
            @include ('company.profile.approval_status.work_agreements_warranty')
            
            <?php /* 12-3-2020 Added start */ ?>
            <?php /* @include ('company.profile.approval_status.insurance_documents') */ ?>
            <?php /* 12-3-2020 Added end */ ?>
            
            @include ('company.profile.approval_status.subcontractor_agreement')
            @include ('company.profile.approval_status.additional_owners_registration')
        </ul>
    </div>
</div>

{!! Form::open(['url' => 'account/application/insurance-mark-as-completed', 'class' => 'module_form', 'id' => 'mark_as_completed_insurance_form']) !!}
{!! Form::hidden('insurance_type', null, ['id' => 'insurance_type']) !!}
{!! Form::close() !!}
@endif


@push('company_document_approval_status_js')
<script type="text/javascript">
    $(function (){
        $(".close_current_modal").on("click", function (){
            $(this).parents(".modal").modal("hide");
            var new_modal_id = $(this).attr("data-target");
            console.log (new_modal_id);
        });
        
        $(".mark_as_completed_insurance").on("click", function (){
            var insurance_type = $(this).data("type");
            
            $("#mark_as_completed_insurance_form #insurance_type").val(insurance_type);
            
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#188ae2",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, I've done this!"
            }).then(function (t) {
                if (typeof t.value != 'undefined') {
                    $('#mark_as_completed_insurance_form').submit();
                }
            });
        });
    });
</script>
@endpush