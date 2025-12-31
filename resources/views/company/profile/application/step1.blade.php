{!! Form::model($company_information, ['url' => 'account/application/company-information','id' =>
'company_information_form', 'class' => 'module_form', 'files' => true])
!!}

<h4 class="mb-3">Company Information</h4>
@if($company->number_of_owners >= 1) <div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Company Owner #1 Full Name: <span class="required">*</span></label>
            {!! Form::text("company_owner_1_full_name", null, ['class' => 'form-control',
            'required' => true]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Company Owner #1 Email Address: <span class="required">*</span></label>
            {!! Form::email("company_owner_1_email", null, ['class' => 'form-control owner_emails', 'id' => 'owner_email_1', 'required' => true]) !!}
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Company Owner #1 Phone: <span class="required">*</span></label>
            {!! Form::text("company_owner_1_phone", null, ['class' => 'form-control owner_phone', 'id' => 'owner_phone_1', 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000', 'required' => true]) !!}
        </div>
    </div>
</div>
@endif


@if($company->number_of_owners >= 2) <div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Company Owner #2 Full Name: <span class="required">*</span></label>
            {!! Form::text("company_owner_2_full_name", null, ['class' => 'form-control',
            'required' => true, 'data-parsley-group' => 'step_1']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Company Owner #2 Email Address: <span class="required">*</span></label>
            {!! Form::email("company_owner_2_email", null, ['class' => 'form-control owner_emails', 'id' => 'owner_email_2', 'required' => true, 'data-parsley-group' => 'step_1']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Company Owner #2 Phone: <span class="required">*</span></label>
            {!! Form::text("company_owner_2_phone", null, ['class' => 'form-control owner_phone', 'id' => 'owner_phone_2', 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000', 'required' => true, 'data-parsley-group' => 'step_1']) !!}
        </div>
    </div>
</div>
@endif

@if($company->number_of_owners >= 3) <div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Company Owner #3 Full Name: <span class="required">*</span></label>
            {!! Form::text("company_owner_3_full_name", null, ['class' => 'form-control', 'id' => 'owner_email_2', 'required' => true, 'data-parsley-group' => 'step_1']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Company Owner #3 Email Address: <span class="required">*</span></label>
            {!! Form::email("company_owner_3_email", null, ['class' => 'form-control owner_emails', 'id' => 'owner_email_3', 'required' => true, 'data-parsley-group' => 'step_1']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Company Owner #3 Phone: <span class="required">*</span></label>
            {!! Form::text("company_owner_3_phone", null, ['class' => 'form-control owner_phone', 'id' => 'owner_phone_3', 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000', 'required' => true, 'data-parsley-group' => 'step_1']) !!}
        </div>
    </div>
</div>
@endif


@if($company->number_of_owners >= 4) <div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Company Owner #4 Full Name: <span class="required">*</span></label>
            {!! Form::text("company_owner_4_full_name", null, ['class' => 'form-control',
            'required' => true]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Company Owner #4 Email Address: <span class="required">*</span></label>
            {!! Form::email("company_owner_4_email", null, ['class' => 'form-control owner_emails', 'id' => 'owner_email_3', 'required' => true, 'data-parsley-group' => 'step_1']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Company Owner #4 Phone: <span class="required">*</span></label>
            {!! Form::text("company_owner_4_phone", null, ['class' => 'form-control owner_phone', 'id' => 'owner_phone_3', 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000', 'required' => true, 'data-parsley-group' => 'step_1']) !!}
        </div>
    </div>
</div>
@endif



<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="">Legal Company Name: <span class="required">*</span></label>
            {!! Form::text("legal_company_name", null, ['class' => 'form-control', 'required' => true]) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="">EIN (Employer Identification Number): <span class="required">*</span></label>
            {!! Form::text("ein", null, ['class' => 'form-control', 'required' => true, 'data-toggle' => 'input-mask', 'data-mask-format' => '00-0000000', 'data-parsley-minlength' => 10]) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="">Company Start Date (MM/YYYY): <span class="required">*</span></label>
            {!! Form::text("company_start_date", null, ['class' => 'form-control', 'required' => true, 'data-toggle' => 'input-mask', 'data-mask-format' => '00/0000', 'maxlength' => '7']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="">Main Company Telephone: <span class="required">*</span></label>
            {!! Form::text("main_company_telephone", null, ['class' => 'form-control', 'required' => true, 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="">Company Website:</label>
            {!! Form::text("website", null, ['class' => 'form-control']) !!}
            <span class="help-block text-info"><small>Keep blank if no website</small></i></span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="">Company Mailing Address: <span class="required">*</span></label>
            {!! Form::text("mailing_address", null, ['class' => 'form-control',
            'required' => true]) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="">Suite:</label>
            {!! Form::text("suite", null, ['class' => 'form-control', 'required' => false])
            !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="">City: <span class="required">*</span></label>
            {!! Form::text("city", null, ['class' => 'form-control', 'required' => true])
            !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="">State: <span class="required">*</span></label>
            {!! Form::select("state_id", $states, null, ['class' => 'form-control custom-select',
            'required' => true,
            'placeholder' => 'Select',
            ]) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="">County: <span class="required">*</span></label>
            {!! Form::text("county", null, ['class' => 'form-control', 'required' => true, 'id' => 'txt_county']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="">Zip Code: <span class="required">*</span></label>
            {!! Form::text("zipcode", null, [
            'class' => 'form-control',
            'required' => true,
            'data-toggle' => 'input-mask',
            'data-mask-format' => '00000'
            ]) !!}
        </div>
    </div>

</div>

<div class="card card-border card-primary mt-3">
    <div class="card-header border-primary bg-transparent">
        <h3 class="card-title text-primary mb-0">Internal Contact</h3>
        <p class="text-black-50 mb-0">
            Please list an internal contact within your company that can always be reached if needed.
            <a href="javascript:;" data-toggle="tooltip" title="Please assign a person we can always get in touch with at a moment's notice in case we call you for an expert opinion, have an immediate need to ask questions regarding helping a consumer or to highlight your company as a trusted expert on any of our media outlets."><i class="fas fa-question-circle"></i></a>
            <br />
            (For our office internal use only) Please no answering service.
        </p>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Internal Contact Full Name: <span class="required">*</span></label>
                    {!! Form::text("internal_contact_fullname", null, ['class' =>
                    'form-control', 'required' => true]) !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Internal Contact Email: <span class="required">*</span></label>
                    {!! Form::email("internal_contact_email", null, ['class' =>
                    'form-control', 'required' => true]) !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Internal Contact Phone: <span class="required">*</span></label>
                    {!! Form::text("internal_contact_phone", null, ['class' =>
                    'form-control',
                    'required' => true,
                    'data-toggle' => 'input-mask',
                    'data-mask-format' => '(000) 000-0000'
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
</div>


<button type="submit" class="btn btn-info float-md-right last_input step1_submit">Save & Next</button>


{!! Form::close() !!}

@push('page_scripts')

<script type="text/javascript">
    $(function() {

        $('#company_information_form').submit(function(){
            // Ajax call of step 1 [Start]

            $(".step1_submit").attr('disabled', true).html('Processing... <i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: $('#company_information_form').attr('action'),
                type: 'POST',
                data: $('#company_information_form').serialize(),
                success: function(data){
                    if (data.status == 1){
                        $(".owner_emails").removeAttr('style');
                        $('.step_6_county').html($('#txt_county').val());
                        slick_next();
                    }else{
                        Swal.fire({
                            title: data.title,
                            type: data.type,
                            text: data.text
                        });
                        
                        if (typeof data.field_id !== 'undefined'){
                            $("#"+data.field_id).css({'border': '1px solid #ff0000'});
                        }
                    }

                    $(".step1_submit").removeAttr('disabled').html('Save & Next');

                },
                error: function(e){
                    alert ('error');
                    $(".step1_submit").removeAttr('disabled').html('Save & Next');
                },
            });
            // Ajax call of step 1 [End]
            return false;
        });
    });
</script>
@endpush
