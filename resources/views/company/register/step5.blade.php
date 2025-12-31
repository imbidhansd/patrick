{!! Form::open(['url' => 'register/step5','id' => 'step5_form', 'class' => 'module_form', 'files' => true]) !!}

<h4>Category Listings</h4>
<h5>Please list a secondary category of services you provide:</h5>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="main_category">Secondary Category: <span class="required">*</span></label>

            {!! Form::select('secondary_main_category_id', [], null, ['id' => 'secondary_main_category_id','class' =>
            'form-control custom_select last_input', 'placeholder' => 'Select']) !!}

        </div>
    </div>
</div>

<div class="card">
    <div class="card-body secondary_service_category_container"></div>
</div>

<div class="clearfix">&nbsp;</div>
<button type="button" class="btn btn-dark float-md-left back_btn">Back</button>
<button type="submit" class="btn btn-info float-md-right last_input step5_submit btn-bg-default">Save & Next</button>

{!! Form::close() !!}

@push('page_scripts')

<script type="text/javascript">
    $(function(){



        // Change Event Secondary Main Category Select Box
        $(document).on('change', '#secondary_main_category_id', function() {
            $(".secondary_service_category_container").html('');

            $.ajax({
                type: 'post',
                url: '{{ url("get-service-category-list") }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'main_category_id': $('#secondary_main_category_id').val(),
                    'step_no': 'step-5'
                },
                success: function(data) {
                    $(".secondary_service_category_container").html(data);
                    refresh_slick_content();
                },
                error: function(data) {
                    alert('error');
                },
            });
        });


        // Ajax call to get data for next Step 6
        function call_ajax_for_step6(){

            // If No Secondary Category Selected and Click on Next
            if ($('#secondary_main_category_id option:checked').text() == 'None') {
                // Move to Step 7
                slick_slide_to(6); // 6 === Slide 7 (map slide)
                return false;
            }



            // If secondary main category selected and check for checkbox selection
            if ($('#secondary_main_category_id').val() != '' && $('.chk_service_category_id_step_2:checked').length > 0) {

                // Check for next Step 6 Or 7

                if ($('#secondary_main_category_id option').length >= 3) {
                    // Ajax for getting Categories for Step 6
                    var data = {
                        '_token': '{{ csrf_token() }}',
                        'main_category_id': $('#main_category_id').val(),
                        'secondary_main_category_id': $('#secondary_main_category_id').val(),
                        'top_level_category_ids[]': []
                    };

                    $(".chk_top_level_category_id:checked").each(function() {
                        data['top_level_category_ids[]'].push($(this).val());
                    });

                    $.ajax({
                        type: 'post',
                        url: '{{ url("get-rest-category-list") }}',
                        data: data,
                        success: function(data) {
                            $('.rest_service_category_container').html(data);
                            $('.include_rest_categories:checked').trigger('click');
                            refresh_slick_content();
                            slick_next();
                        },
                        error: function(e) {
                            alert('error');
                        },
                    });

                } else {
                    // Move to Step 7
                    refresh_slick_content();
                    slick_slide_to(6); // 6 === Slide 7 (map slide)
                }

            } else {
                Swal.fire(
                    'Warning',
                    'Please select at least one category',
                    'warning'
                );
                return false;
            }

        }

        // Submit call of step 5 form
        $('#step5_form').submit(function(){
            $(".step5_submit").attr('disabled', true).html('Processing... <i class="fas fa-spinner fa-spin"></i>');
            $.ajax({
                type: 'POST',
                url: $('#step5_form').attr('action'),
                data: $('#step5_form').serialize(),
                success: function(data) {
                    call_ajax_for_step6();
                    $(".step5_submit").removeAttr('disabled').html('Save & Next');
                },
                error: function(e) {
                    Swal.fire(
                        'Warning',
                        'Error while processing',
                        'warning'
                    );
                    $(".step5_submit").removeAttr('disabled').html('Save & Next');
                },
            });
            return false;
        });

    });
</script>


@endpush
