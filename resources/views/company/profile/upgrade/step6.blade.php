{!! Form::open(['url' => 'account/upgrade/step6','id' => 'step6_form', 'class' => 'module_form', 'files' => true]) !!}
<h5>Would you like to add any of these additional service category listings? <span class="required">*</span></h5>

<div class="radio radio-success">
    <input type="radio" name="include_rest_categories" class="include_rest_categories" id="include_rest_categories_yes" value="yes" required data-parsley-errors-container="#step6-include-rest-categories-error-container" />
    <label for="include_rest_categories_yes">
        Yes
    </label>
</div>

<div class="radio radio-danger">
    <input type="radio" name="include_rest_categories" class="include_rest_categories last_input" id="include_rest_categories_no" required data-parsley-errors-container="#step6-include-rest-categories-error-container" value="no"/>
    <label for="include_rest_categories_no">
        No
    </label>
</div>

<div id="step6-include-rest-categories-error-container"></div>


<div class="card">
    <div class="card-body rest_service_category_container">
    </div>
</div>


<div class="clearfix">&nbsp;</div>
<button type="button" class="btn btn-dark float-md-left back_btn">Back</button>
<button type="submit" class="btn btn-info float-md-right last_input step6_submit">Save & Next</button>

{!! Form::close() !!}

@push('page_scripts')

<script type="text/javascript">
    $(function(){


        $('.include_rest_categories').click(function() {
            if ($(this).val() == 'no') {
                $('.rest_service_category_container').slideUp(function(){ refresh_slick_content(); });
                $('.chk_service_category_id_step_6').removeAttr('required');
            } else if ($(this).val() == 'yes') {
                $('.rest_service_category_container').slideDown(function() { refresh_slick_content(); });
                $('.chk_service_category_id_step_6').attr('required', true);
            }
        });


        $(document).on('change','.rest_main_category', function(){
            var flag = $(this).is(':checked');
            var list = $(this).closest('.chk_main_cat').find('.service_category_item_list');
            if (flag == true){
                list.removeClass('d-none');
            }else{
                list.addClass('d-none');
            }
            refresh_slick_content();
        });


        $('.next_btn_3_4').click(function() {

            if ($('.include_rest_categories:checked').val() == 'no') {
                $('.step-carousel').trigger('next.owl.carousel');
            } else if ($('.include_rest_categories:checked').val() == 'yes' && $('.chk_service_category_id_step_4:checked').length > 0) {
                $('.step-carousel').trigger('next.owl.carousel');
            } else {
                Swal.fire(
                    'Warning',
                    'Please select at least one category',
                    'warning'
                );
                return false;
            }
        });

        // Submit call of step 5 form
        $('#step6_form').submit(function(){
            $(".step6_submit").attr('disabled', true).html('Processing... <i class="fas fa-spinner fa-spin"></i>');
            $.ajax({
                type: 'POST',
                url: $('#step6_form').attr('action'),
                data: $('#step6_form').serialize(),
                success: function(data) {
                    refresh_slick_content();
                    slick_next();
                    $(".step6_submit").removeAttr('disabled').html('Save & Next');
                },
                error: function(e) {
                    Swal.fire(
                                'Warning',
                                'Error while processing',
                                'warning'
                            );
                    $(".step6_submit").removeAttr('disabled').html('Save & Next');
                },
            });
            return false;
        });

    });
</script>


@endpush
