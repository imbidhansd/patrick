{!! Form::open(['url' => route('post-background-check-step3'),'id' => 'step3_form', 'class' => 'module_form', 'files' => true]) !!}

<h4>Other Information</h4>


<div class="form-group">
    <label>Have you ever been convicted of fraud? <span class="required">*</span></label>
    <div class="radio radio-primary radio-circle">
        {!! Form::radio('convicted_in_fraud', 'Yes', null, ['id' => 'yes_convicted_in_fraud', 'required' => true, 'data-parsley-errors-container'=>'#convicted_in_fraud_error']) !!}
        <label for="yes_convicted_in_fraud">Yes</label>
    </div>
    <div class="radio radio-primary radio-circle">
        {!! Form::radio('convicted_in_fraud', 'No', null, ['id' => 'no_convicted_in_fraud', 'required' => true, 'data-parsley-errors-container'=>'#convicted_in_fraud_error']) !!}
        <label for="no_convicted_in_fraud">No</label>
    </div>
    <div id="convicted_in_fraud_error"></div>
</div>

<div class="clearfix">&nbsp;</div>

<div class="form-group">
    <label>Have you ever been convicted of a felony? <span class="required">*</span></label>
    <div class="radio radio-primary radio-circle">
        {!! Form::radio('convicted_in_felony', 'Yes', null, ['id' => 'yes_convicted_in_felony', 'required' => true, 'data-parsley-errors-container'=>'#convicted_in_felony_error']) !!}
        <label for="yes_convicted_in_felony">Yes</label>
    </div>
    <div class="radio radio-primary radio-circle">
        {!! Form::radio('convicted_in_felony', 'No', null, ['id' => 'no_convicted_in_felony', 'required' => true, 'data-parsley-errors-container'=>'#convicted_in_felony_error']) !!}
        <label for="no_convicted_in_felony">No</label>
    </div>
    <div id="convicted_in_felony_error"></div>
</div>

<div class="clearfix">&nbsp;</div>

<div class="form-group">
    <label>Have you filed for bankruptcy in the last 7 years? <span class="required">*</span></label>
    <div class="radio radio-primary radio-circle">
        {!! Form::radio('bankruptcy', 'Yes', null, ['id' => 'yes_bankruptcy', 'required' => true, 'data-parsley-errors-container'=>'#bankruptcy_error']) !!}
        <label for="yes_bankruptcy">Yes</label>
    </div>
    <div class="radio radio-primary radio-circle">
        {!! Form::radio('bankruptcy', 'No', null, ['id' => 'no_bankruptcy', 'required' => true, 'data-parsley-errors-container'=>'#bankruptcy_error']) !!}
        <label for="no_bankruptcy">No</label>
    </div>
    <div id="bankruptcy_error"></div>
</div>

<div class="clearfix">&nbsp;</div>

<div class="form-group">
    <label>Have you operated this business or a similar business under any other business name?  <span class="required">*</span></label>
    <div class="radio radio-primary radio-circle">
        {!! Form::radio('other_business_name', 'Yes', null, ['class' => 'other_business_name', 'id' => 'yes_other_business_name', 'required' => true, 'data-parsley-errors-container'=>'#other_business_name_error']) !!}
        <label for="yes_other_business_name">Yes</label>
    </div>
    <div class="radio radio-primary radio-circle">
        {!! Form::radio('other_business_name', 'No', null, ['class' => 'other_business_name', 'id' => 'no_other_business_name', 'required' => true, 'data-parsley-errors-container'=>'#other_business_name_error']) !!}
        <label for="no_other_business_name">No</label>
    </div>
    <div id="other_business_name_error"></div>
</div>

<div id="other_business_name_list" style="display: none;">
    <div class="form-group">
        {!! Form::label('Please list all business names:') !!} <span class="required">*</span>
        {!! Form::textarea('business_name_list', null, ['class' => 'form-control', 'rows' => '4', 'id' => 'business_name_list']) !!}
    </div>
</div>
<hr/>

<button type="button" class="btn btn-dark float-md-left back_btn">Back</button>
<button type="submit" class="btn btn-info float-md-right step3_submit last_input">Save & Next</button>
{!! Form::close() !!}

@push('page_scripts')
<script type="text/javascript">
    $(function(){

         //$("#get_started_btn").trigger("click");

        $(".other_business_name").on("change", function (){
            var radio_value = $(this).val();

            if (radio_value == 'Yes'){
                $("#other_business_name_list").show();
                $("#other_business_name_list #business_name_list").attr('required', true);
            } else {
                $("#other_business_name_list").hide();
                $("#other_business_name_list #business_name_list").attr('required', false);
                $("#other_business_name_list #business_name_list").val('');
            }
            refresh_slick_content();
        });

        $(".changed_name").on("change", function (){
            var radio_value = $(this).val();

            if (radio_value == 'Yes'){
                $("#other_name_list").show();
                $("#other_name_list #changed_name_list").attr('required', true);
            } else {
                $("#other_name_list").hide();
                $("#other_name_list #changed_name_list").attr('required', false);
                $("#other_name_list #changed_name_list").val('');
            }
            refresh_slick_content();
        });


        // Step 1 Form Submission Event
        $('#step3_form').submit(function(){

            $(".step3_submit").attr('disabled', true).html('Processing... <i class="fas fa-spinner fa-spin"></i>');

            var form = $('#step3_form')[0]; // You need to use standard javascript object here
            var formData = new FormData(form);

            $.ajax({
                url: $('#step3_form').attr('action'),
                type: 'POST',
                data: formData,
                contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                processData: false, // NEEDED, DON'T OMIT THIS
                success: function(data){
                    if (data.status == 0){
                        Swal.fire(
                            'Warning',
                            data.message,
                            'error'
                            );
                    }else{
                        slick_next();
                    }
                    $(".step3_submit").removeAttr('disabled').html('Save & Next');
                },
                error: function(e){
                    Swal.fire(
                        'Warning',
                        'Error while processing',
                        'error'
                        );
                    $(".step3_submit").removeAttr('disabled').html('Save & Next');
                },
            });
            // Ajax call of step 1 [End]
            return false;
            
        });

    });
</script>

@endpush
