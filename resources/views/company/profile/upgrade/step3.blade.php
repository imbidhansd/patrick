{!! Form::open(['url' => 'account/upgrade/step3','id' => 'step3_form', 'class' => 'module_form', 'files' => true]) !!}

<h5>Categories</h5>
<div class="row">
    <div class="col-md-6">
        <label>Type Of Business</label>
        <h6 class="text-info mt-0">{{ $companyObj->trade->title }}</h6>

        <div class="form-group d-none">
            {!! Form::label('Please select type of business*') !!}
            {!! Form::select('trade_id', $trades, $companyObj->trade_id, ['id' => 'trade_id','class' => 'form-control',
            'required' => true, 'placeholder' => 'Select']) !!}
        </div>
    </div>
</div>
<div class="clearfix">&nbsp;</div>
<div class="row">
    <div class="col-md-12 top_level_categories_container">


    </div>
</div>

<div class="clearfix">&nbsp;</div>
<button type="button" class="btn btn-dark float-md-left back_btn">Back</button>
<button type="submit" class="btn btn-info float-md-right last_input step3_submit">Save & Next</button>

{!! Form::close() !!}

@push('page_scripts')

<script type="text/javascript">
    $(function(){

        $('#trade_id').change(function(){
            $('.top_level_categories_container').html('');
            if ($('#trade_id').val() > 0){
                $.ajax({
                    type: 'post',
                    url: '{{ url("get-top-level-category-list") }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'trade_id': $('#trade_id').val(),
                        'show_back_btn': 'yes',
                    },
                    success: function(data){
                        $('.top_level_categories_container').html(data);
                        refresh_slick_content();
                     },
                    error: function(data){ alert ('error'); },
                });
            }else{

            }

        });

        $('#trade_id').trigger('change');

        // Ajax call to get data for next Step 4
        function call_ajax_for_step4(){

            var data = { '_token': '{{ csrf_token() }}' ,'top_level_category_ids[]' : []};
            $(".chk_top_level_category_id:checked").each(function() {
                data['top_level_category_ids[]'].push($(this).val());
            });

            $.ajax({
                type: 'post',
                url: '{{ url("get-main-category-list") }}',
                data: data,
                success: function(data){
                    $('#main_category_id').html(data);
                    $('#main_category_id').trigger('change');
                    slick_next();
                },
                error: function(e){
                    alert ('error');
                },
            });
        }

        $('#step3_form').submit(function(){
            $(".step3_submit").attr('disabled', true).html('Processing... <i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                type: 'POST',
                url: $('#step3_form').attr('action'),
                data: $('#step3_form').serialize(),
                success: function(data){
                    if (data.status == 0){
                        Swal.fire(
                                'Warning',
                                data.message,
                                'warning'
                            );
                    }else{
                        call_ajax_for_step4();
                    }
                    $(".step3_submit").removeAttr('disabled').html('Save & Next');
                },
                error: function(e){
                    Swal.fire(
                                'Warning',
                                'Error while processing',
                                'warning'
                            );
                    $(".step3_submit").removeAttr('disabled').html('Save & Next');
                },
            });

            return false;

        })
    });
</script>

@endpush
