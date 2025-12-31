{!! Form::model($company_lead_notifications, ['url' => 'account/application/lead-notifications','id' =>
'company_lead_notification_form', 'class' => 'module_form', 'files' => true])
!!}

<h4 class="mb-3">Company Page And Lead Notifications</h4>
<p>All leads are emailed to you automatically once a visitor has confirmed their request.</p>
<p class="text-danger">Please indicate the MAIN email address where all leads should be sent.</p>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Please enter MAIN email address: <span class="required">*</span></label>
            {!! Form::email('main_email_address', null, ['class' => 'form-control', 'required' => true, 'id' => 'txt_main_email_address', 'data-modal_show' => 'true']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <div class="form-group mb-0">
            <label>Would you like more than one person within your company to receive a copy of all
                emailed leads? <span class="required">*</span></label>
            <div class="radio radio-primary radio-circle">
                {!! Form::radio('receive_a_copy', 'yes' , null, ['id' => 'another_email_yes', 'required' =>
                true, 'class' => 'rdo_receive_a_copy', 'data-parsley-errors-container' => '#rdo_receive_a_copy_error']) !!}
                <label for="another_email_yes">Yes</label>
            </div>
            <div class="radio radio-primary radio-circle">
                {!! Form::radio('receive_a_copy', 'no' , null, ['id' => 'another_email_no', 'required' =>
                true, 'class' => 'rdo_receive_a_copy', 'data-parsley-errors-container' => '#rdo_receive_a_copy_error']) !!}
                <label for="another_email_no">No</label>
            </div>
        </div>
        <div id="rdo_receive_a_copy_error"></div>


        <div class="{{ !is_null($company_lead_notifications) && $company_lead_notifications->receive_a_copy == 'yes' ? : 'hide' }}"
            id="other_email_address_container">

            <p class="text-danger">Attention: Leads cannot be sent to anyone outside of your company. For
                obvious reasons, we do not allow companies to sub-contract work or pass leads to companies
                that are not approved by TrustPatrick.com.</p>
            <div class="form-group">
                <label>Please select all that apply:</label>

                @if ($company_item->number_of_owners > 1)
                @for ($i=2; $i <= $company_item->number_of_owners; $i++)
                <div class="checkbox checkbox-primary ">
                    {!! Form::checkbox('owner_' . $i, 'yes', null, ['id' => 'another_email_ids_owner_' . $i,
                    'class' => 'other_email_option']) !!}
                    <label for="another_email_ids_owner_{{ $i }}">Applicant/Owner {{ $i }}</label>
                </div>
                @endfor
                @endif

                <div class="checkbox checkbox-primary ">
                    {!! Form::checkbox('office_manager', 'yes', null, ['id' => 'another_email_ids_manager',
                    'class' => 'other_email_option']) !!}
                    <label for="another_email_ids_manager">Office Manager</label>
                </div>

                <div class="checkbox checkbox-primary ">
                    {!! Form::checkbox('sales_manager', 'yes', null, ['id' =>
                    'another_email_ids_sales_manager', 'class' => 'other_email_option']) !!}
                    <label for="another_email_ids_sales_manager">Sales Manager</label>
                </div>

                <div class="checkbox checkbox-primary ">
                    {!! Form::checkbox('estimators_sales_1', 'yes', null, ['id' =>
                    'another_email_ids_sales', 'class' => 'other_email_option']) !!}
                    <label for="another_email_ids_sales">Estimator/sales</label>
                </div>

                <div class="checkbox checkbox-primary ">
                    {!! Form::checkbox('estimators_sales_2', 'yes', null, ['id' =>
                    'another_email_ids_sales2', 'class' => 'other_email_option']) !!}
                    <label for="another_email_ids_sales2">Estimator/sales 2</label>
                </div>
            </div>

            @if ($company_item->number_of_owners > 1)
            @for ($i=2; $i <= $company_item->number_of_owners; $i++)
            @php $field = 'owner_' . $i @endphp
            <div class="card card-border card-primary {{ !is_null($company_lead_notifications) && $company_lead_notifications->$field == 'yes' ? : 'hide' }}"
                id="another_email_ids_owner_{{ $i }}_div">
                <div class="card-header border-primary bg-transparent">
                    <h3 class="card-title text-primary mb-0">
                        Applicant/Owner{{ $i }} Info
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Name: <span class="required">*</span></label>
                            {!! Form::text('owner_' . $i . '_name', null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="col-md-6">
                            <label>Email: <span class="required">*</span></label>
                            {!! Form::email('owner_' . $i . '_email', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
            @endfor
            @endif

            <div class="card card-border card-primary {{ !is_null($company_lead_notifications) && $company_lead_notifications->office_manager == 'yes' ? : 'hide' }}"
                id="another_email_ids_manager_div">
                <div class="card-header border-primary bg-transparent">
                    <h3 class="card-title text-primary mb-0">
                        Office Manager Info
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Name: <span class="required">*</span></label>
                            {!! Form::text('office_manager_name', null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="col-md-6">
                            <label>Email: <span class="required">*</span></label>
                            {!! Form::email('office_manager_email', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-border card-primary {{ !is_null($company_lead_notifications) && $company_lead_notifications->sales_manager == 'yes' ? : 'hide' }}"
                id="another_email_ids_sales_manager_div">
                <div class="card-header border-primary bg-transparent">
                    <h3 class="card-title text-primary mb-0">
                        Sales Manager Info
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Name: <span class="required">*</span></label>
                            {!! Form::text('sales_manager_name', null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="col-md-6">
                            <label>Email: <span class="required">*</span></label>
                            {!! Form::email('sales_manager_email', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-border card-primary {{ !is_null($company_lead_notifications) && $company_lead_notifications->estimators_sales_1 == 'yes' ? : 'hide' }}"
                id="another_email_ids_sales_div">
                <div class="card-header border-primary bg-transparent">
                    <h3 class="card-title text-primary mb-0">
                        Estimator/sales Info
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Name: <span class="required">*</span></label>
                            {!! Form::text('estimators_sales_1_name', null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="col-md-6">
                            <label>Email: <span class="required">*</span></label>
                            {!! Form::email('estimators_sales_1_email', null, ['class' => 'form-control'])
                            !!}
                        </div>
                    </div>
                </div>
                </div>

            <div class="card card-border card-primary {{ !is_null($company_lead_notifications) && $company_lead_notifications->estimators_sales_2 == 'yes' ? : 'hide' }}"
                id="another_email_ids_sales2_div">
                <div class="card-header border-primary bg-transparent">
                    <h3 class="card-title text-primary mb-0">
                        Estimator/sales 2 Info
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Name: <span class="required">*</span></label>
                            {!! Form::text('estimators_sales_2_name', null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="col-md-6">
                            <label>Email: <span class="required">*</span></label>
                            {!! Form::email('estimators_sales_2_email', null, ['class' => 'form-control'])
                            !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>

<button type="button" class="btn btn-dark back_btn">Back</button>
<button type="submit" class="btn btn-info float-md-right last_input step5_submit">Save & Next</button>

{!! Form::close() !!}


@push('page_scripts')
<script type="text/javascript">
    $(function(){
        $("#txt_main_email_address").on("focus", function (){
            if ($(this).data('modal_show') == true){
                $('#warningModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }
            $(this).data('modal_show', 'false').attr('data-modal_show', 'false');
        });

        $('#another_email_yes').click(function(){
            $('#other_email_address_container').show().find('input[type="checkbox"]').removeAttr('required');
            refresh_slick_content();
        });
        $('#another_email_no').click(function(){
            $('#other_email_address_container').hide();
            $('#other_email_address_container').find('input').removeAttr('required');
            refresh_slick_content();
        });

        $('.other_email_option').change(function(){
            var id=$(this).attr('id') + '_div';
            if ($(this).is(':checked') == true){
                $('#' + id).show();
                $('#' + id).find('input').attr('required', true);

                //$("#warningModal").modal("show");
                $('#warningModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }else{
                $('#' + id).hide();
                $('#' + id).find('input').removeAttr('required');
            }
            refresh_slick_content();
        });


        // Submit Step

        $('#company_lead_notification_form').submit(function(){
            // Ajax call of step 1 [Start]

            var form = $('#company_lead_notification_form')[0]; // You need to use standard javascript object here
            var formData = new FormData(form);
            $(".step5_submit").attr('disabled', true).html('Processing... <i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: $('#company_lead_notification_form').attr('action'),
                type: 'POST',
                data: formData,
                contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                processData: false, // NEEDED, DON'T OMIT THIS
                success: function(data){
                    slick_next();
                    
                    $("#terms1, #terms2").prop("checked", false);
                    $(".step5_submit").removeAttr('disabled').html('Save & Next');
                },
                error: function(e){
                    alert ('error');
                    $(".step5_submit").removeAttr('disabled').html('Save & Next');
                },
            });
            // Ajax call of step 1 [End]
            return false;
        });

    });

</script>
@endpush
