{!! Form::model($company_insurance, ['url' => 'account/application/company-insurance','id' =>
'company_insurance_form', 'class' => 'module_form', 'files' => true])
!!}

<?php
    $trade_word = 'General';
    if ($company_item->trade_id == 2){
        $trade_word = 'Professional';
    }
?>

<h4 class="mb-3">Insurance Information</h4>
<div class="form-group mb-0">
    <label>Do you carry Professional liability/E&O/Malpractice Insurance? <span class="required">*</span></label>

    <div class="radio radio-primary radio-circle">

        {!! Form::radio("general_liability_insurance_and_worker_compensation_insurance", 'Yes', null, ['id'
        =>
        'carry_both_general_liability_insurance_and_worker_compensation_insurance_yes', 'class' =>
        'carry_both_general_liability_insurance_and_worker_compensation_insurance', 'required' => true,
        'data-parsley-group' => 'step_3',
        'data-parsley-errors-container' => '#carry_both_general_liability_insurance_and_worker_compensation_insurance_error'
        ]) !!}
        <label for="carry_both_general_liability_insurance_and_worker_compensation_insurance_yes">Yes</label>
    </div>

    <div class="radio radio-primary radio-circle">
        {!! Form::radio("general_liability_insurance_and_worker_compensation_insurance",
        'No, We do not have any employees', null, ['id' =>
        'carry_both_general_liability_insurance_and_worker_compensation_insurance_no_employee',
        'class' => 'carry_both_general_liability_insurance_and_worker_compensation_insurance',
        'required' => true, 'data-parsley-group' => 'step_3',
        'data-parsley-errors-container' => '#carry_both_general_liability_insurance_and_worker_compensation_insurance_error'
        ]) !!}
        <label for="carry_both_general_liability_insurance_and_worker_compensation_insurance_no_employee">No</label>
    </div>
</div>


<div id="carry_both_general_liability_insurance_and_worker_compensation_insurance_error"></div>


<div class="clearfix">&nbsp;</div>


<?php
    $hide_class = 'hide';

    if (!is_null($company_insurance) && $company_insurance->general_liability_insurance_and_worker_compensation_insurance == 'Yes'){
        $hide_class = '';
    }

?>

<div class="card card-border card-primary {{ $hide_class }}" id="general_liability_insurance_agent_agency">
    <div class="card-header border-primary bg-transparent">
        <h3 class="card-title text-primary mb-0">{{ $trade_word }} Liability Insurance Agent/Agency</h3>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Insurance Agent/Agency Name: <span class="required">*</span></label>
                    {!! Form::text('general_liability_insurance_agent_agency_name', null, ['class' =>
                    'form-control']) !!}
                </div>

                <div class="form-group">
                    <label for="">Insurance Agent/Agency Phone Number: <span class="required">*</span></label>
                    {!! Form::text('general_liability_insurance_agent_agency_phone_number', null, [
                    'class' => 'form-control',
                    'data-toggle' => 'input-mask',
                    'data-mask-format' => '(000) 000-0000'
                    ]) !!}
                </div>
            </div>

            <div class="col-md-6">
                <label>&nbsp;</label>
                <div class="alert alert-info">As a requirement for approval and at no cost to you please
                    have your insurance agent add trustpatrick.com as a certificate holder to your {{ $trade_word }}
                    Liability Insurance.<br /> Upon completion of application, a pre-filled insurance
                    request form will be emailed to you for your convenience! Just forward to your insurance
                    agent!</div>
            </div>
        </div>
    </div>
</div>


<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>

<button type="button" class="btn btn-dark back_btn">Back</button>
<button type="submit" class="btn btn-info float-md-right last_input">Save & Next</button>



{!! Form::close() !!}

@push('page_scripts')
<script type="text/javascript">
    $(function(){

        $('.carry_both_general_liability_insurance_and_worker_compensation_insurance').change(function() {
            switch($(this).val()){
                case 'Yes':
                    $('#general_liability_insurance_agent_agency').show();
                    $('#general_liability_insurance_agent_agency').find('input').attr('required', true);
                    break;
                default:
                    $('#general_liability_insurance_agent_agency').hide();
                    $('#general_liability_insurance_agent_agency').find('input').removeAttr('required');
                    break;
            }
            refresh_slick_content();
        });


        $('#company_insurance_form').submit(function(){
            // Ajax call of step 1 [Start]

            var form = $('#company_insurance_form')[0]; // You need to use standard javascript object here
            var formData = new FormData(form);

            $.ajax({
                url: $('#company_insurance_form').attr('action'),
                type: 'POST',
                data: formData,
                contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                processData: false, // NEEDED, DON'T OMIT THIS
                success: function(data){
                    slick_next();
                },
                error: function(e){
                    alert ('erro');
                },
            });
            // Ajax call of step 1 [End]
            return false;
        });

    });

</script>
@endpush
