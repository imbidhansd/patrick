{!! Form::model($company_licensing, ['url' => 'account/application/company-licensing','id' =>
'company_licensing_form', 'class' => 'module_form', 'files' => true])
!!}

<h4 class="mb-3">{{ $step_2_page_title }}</h4>

@include ('company.profile.application.step_2_1')
@include ('company.profile.application.step_2_2')
@include ('company.profile.application.step_2_3')
@if ($company_item->trade_id == 1) 
@include ('company.profile.application.step_2_4')
@include ('company.profile.application.step_2_5')
@endif

<button type="button" class="btn btn-dark back_btn">Back</button>
<button type="submit" class="btn btn-info float-md-right last_input step2_submit">Save & Next</button>

{!! Form::close() !!}


@push('page_scripts')
<script type="text/javascript">
    $(function() {

        $('#company_licensing_form').submit(function(){

            $(".step2_submit").attr('disabled', true).html('Processing... <i class="fas fa-spinner fa-spin"></i>');

            var form = $('#company_licensing_form')[0]; // You need to use standard javascript object here
            var formData = new FormData(form);

            $.ajax({
                url: $('#company_licensing_form').attr('action'),
                type: 'POST',
                data: formData,
                contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                processData: false, // NEEDED, DON'T OMIT THIS
                success: function(data){
                    slick_next();
                    $(".step2_submit").removeAttr('disabled').html('Save & Next');
                },
                error: function(e){
                    alert ('error');
                    $(".step2_submit").removeAttr('disabled').html('Save & Next');
                },
            });
            // Ajax call of step 1 [End]
            return false;
        });
    });
</script>
@endpush
