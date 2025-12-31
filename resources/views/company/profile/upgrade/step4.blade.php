{!! Form::open(['url' => 'account/upgrade/step4','id' => 'step4_form', 'class' => 'module_form', 'files' => true]) !!}

<h4>Category Listings</h4>
<h5>Please choose the main category of services you provide:</h5>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="main_category">Main Category: <span class="required">*</span></label>
            {!! Form::select('main_category_id', [], null, ['id' => 'main_category_id','class' =>
            'form-control custom-select',
            'required' => true, 'placeholder' => 'Select']) !!}
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body main_service_category_container">
    </div>
</div>


<div class="clearfix">&nbsp;</div>
<button type="button" class="btn btn-dark float-md-left back_btn">Back</button>
<button type="submit" class="btn btn-info float-md-right last_input step4_submit">Save & Next</button>

{!! Form::close() !!}

@push('page_scripts')

<script type="text/javascript">
    $(function(){

        // Change Event Main Category Select Box
        $(document).on('change', '#main_category_id', function() {
            $(".main_service_category_container").html('');
            // For step 5 //
            $(".secondary_service_category_container").html('');
            $('#secondary_main_category_id').html('');
            // For step 5 //
            $.ajax({
                type: 'post',
                url: '{{ url("get-service-category-list") }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'main_category_id': $('#main_category_id').val(),
                    'step_no': 'step-4'
                },
                success: function(data) {
                    $(".main_service_category_container").html(data);
                    refresh_slick_content();
                },
                error: function(data) {
                    alert('error');
                },
            });
        });


        // Ajax call to get data for next Step 5
        function call_ajax_for_step5(){

            // Ajax for getting Main Categories for Step 5 [Start]
            var data = {
                '_token': '{{ csrf_token() }}',
                'main_category_id': $('#main_category_id').val(),
                'top_level_category_ids[]': []
            };
            $(".chk_top_level_category_id:checked").each(function() {
                data['top_level_category_ids[]'].push($(this).val());
            });

            $.ajax({
                type: 'post',
                url: '{{ url("get-main-category-list") }}',
                data: data,
                success: function(data) {
                    if (data == 'false') {
                        // Move to google Map Slide
                        slick_slide_to(6); // 6 === Slide 7 (map slide)

                    } else {
                        $('#secondary_main_category_id').html(data).trigger('change');
                        //$('.secondary_service_category_container').html('');
                        slick_next();
                        refresh_slick_content();
                    }

                },
                error: function(e) {
                    alert('error');
                },
            });
            // Ajax for getting Main Categories for Step 5 [End]
        }


        // Submit call of step 4 form
        $('#step4_form').submit(function(){
            $(".step4_submit").attr('disabled', true).html('Processing... <i class="fas fa-spinner fa-spin"></i>');
            // Ajax for getting Main Categories for Step 4
            $.ajax({
                type: 'POST',
                url: $('#step4_form').attr('action'),
                data: $('#step4_form').serialize(),
                success: function(data) {
                    call_ajax_for_step5();
                    $(".step4_submit").removeAttr('disabled').html('Save & Next');
                },
                error: function(e) {
                    Swal.fire(
                                'Warning',
                                'Error while processing',
                                'warning'
                            );
                    $(".step4_submit").removeAttr('disabled').html('Save & Next');
                },
            });
            return false;
        });
    });
</script>


@endpush
