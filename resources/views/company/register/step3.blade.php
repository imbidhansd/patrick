{!! Form::open(['url' => 'register/step3','id' => 'step3_form', 'class' => 'module_form', 'files' => true]) !!}

<h5 class="hide">Categories</h5>
<div class="row">
    <div class="col-md-6 hide">
        <div class="form-group">
            <label>Please select type of business <span class="required">*</span></label>
            {!! Form::select('trade_id', $trades, null, ['id' => 'trade_id','class' => 'form-control custom-select
            last_input',
            'required' => true,
            'placeholder' => 'Select'
            ]) !!}
        </div>
    </div>
    <div class="col-md-12 top_level_categories_container">
    </div>
</div>


<div class="clearfix">&nbsp;</div>
<button type="button" class="btn btn-dark float-md-left back_btn">Back</button>
<button type="submit" class="btn btn-info float-md-right last_input step3_submit btn-bg-default">Save & Next</button>

{!! Form::close() !!}

@push('page_scripts')

<script type="text/javascript">
    $(function(){       
        $('#trade_id').change(function() {
            $('.top_level_categories_container').html('');
            if ($('#trade_id').val() > 0) {
                $.ajax({
                    type: 'post',
                    url: '{{ url("get-top-level-category-list") }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'trade_id': $('#trade_id').val()
                    },
                    success: function(data) {
                        $('.top_level_categories_container').html(data);
                        refresh_slick_content();
                    },
                    error: function(data) {
                        alert('error');
                    },
                });
            } else {

            }
        });
        $('#trade_id').val(1).trigger('change');
        $('#step3_form').submit(function(){

            $(".step3_submit").attr('disabled', true).html('Processing... <i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                type: 'POST',
                url: $('#step3_form').attr('action'),
                data: $('#step3_form').serialize(),
                success: function(data){

                    if (data.status == '1'){

                        // check for checkbox selection
                        $('#main_category_id').html('');
                        $(".main_service_category_container").html('');

                        $('#secondary_main_category_id').html('');
                        $(".secondary_service_category_container").html('');

                        if ($('.chk_top_level_category_id:checked').length > 0) {

                            var data = {
                                '_token': '{{ csrf_token() }}',
                                'top_level_category_ids[]': []
                            };
                            $(".chk_top_level_category_id:checked").each(function() {
                                data['top_level_category_ids[]'].push($(this).val());
                            });


                            // Ajax for getting Main Categories for Step 2
                            $.ajax({
                                type: 'post',
                                url: '{{ url("get-main-category-list") }}',
                                data: data,
                                success: function(data) {
                                    $('#main_category_id').html(data);
                                    $('#main_category_id').trigger('change');
                                    $(".step3_submit").removeAttr('disabled').html('Save & Next');
                                    refresh_slick_content();
                                    slick_next();
                                },
                                error: function(e) {
                                    alert('error');
                                },
                            });

                        } else {
                            Swal.fire(
                                'Warning',
                                'Please select at least one category',
                                'warning'
                            );
                            $(".step3_submit").removeAttr('disabled').html('Save & Next');
                        }
                    }


                },
                error: function(e){},
            });

            return false;
        });


    });
</script>


@endpush
