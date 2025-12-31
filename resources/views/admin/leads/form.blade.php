<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Full Name') !!}
            {!! Form::text('full_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Full Name', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Email') !!}
            {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Enter Email', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Phone') !!}
            {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => 'Enter Phone', 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000', 'required' => true]) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Additional Phone?') !!}
            <div class="radio radio-primary radio-circle">
                <input type="radio" name="additional_phone" class="additional_phone" value="yes" id="a_yes"  />
                <label for="a_yes">Yes</label>
            </div>

            <div class="radio radio-primary radio-circle">
                <input type="radio" name="additional_phone" class="additional_phone" value="no" id="a_no"  />
                <label for="a_no">No</label>
            </div>
        </div>
    </div>

    <div class="col-md-6" id="additional_phone_number" style="display: none;">
        <div class="form-group">
            {!! Form::label('Additional Phone Number') !!}
            {!! Form::text('additional_phone_number', null, ['class' => 'form-control', 'id' => 'additional_phone_number_field', 'placeholder' => 'Enter Additional Phone Number', 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000', 'required' => false]) !!}
        </div>
    </div>
</div>

<hr />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Service Category type') !!}
            {!! Form::select('service_category_type_id', $service_category_type, null, ['class' => 'form-control custom-select', 'id' => 'service_category_type_id', 'placeholder' => 'Select Service Category Type', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Main Category') !!}
            {!! Form::text('main_category', (isset($formObj) ? $formObj->main_category->title : null), ['class' => 'form-control', 'id' => 'main_category', 'placeholder' => 'All', 'required' => true]) !!}

            {!! Form::hidden('main_category_id', (isset($formObj) ? $formObj->main_category_id : null), ['id' => 'main_category_id_h']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Service Category') !!}
            {!! Form::text('service_category', (isset($formObj) ? $formObj->service_category->title : null), ['class' => 'form-control', 'id' => 'service_category', 'placeholder' => 'All', 'required' => true]) !!}

            {!! Form::hidden('service_category_id', (isset($formObj) ? $formObj->service_category_id : null), ['id' => 'service_category_id_h']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Timeframe') !!}
            {!! Form::select('timeframe', $timeframe, null, ['class' => 'form-control custom-select', 'placeholder' => 'Select Timeframe', 'required' => true]) !!}
        </div>
    </div>
</div>

<hr />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Project Address') !!}
            {!! Form::text('project_address', null, ['class' => 'form-control', 'placeholder' => 'Enter Project Address', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('State') !!}
            {!! Form::select('state_id', $states, null, ['class' => 'form-control custom-select', 'placeholder' => 'Select State', 'required' => false]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('City') !!}
            {!! Form::text('city', null, ['class' => 'form-control', 'placeholder' => 'Enter City', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Zipcode') !!}
            {!! Form::text('zipcode', null, ['class' => 'form-control', 'placeholder' => 'Enter Zipcode', 'required' => true, 'maxlength' => 5, 'data-toggle' => 'input-mask', 'data-mask-format' => '00000']) !!}
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Project Info') !!}
            {!! Form::textarea('content', null, ['class' => 'form-control', 'placeholder' => 'Enter Project Info', 'required' => true]) !!}
        </div>
    </div>
</div>

<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>

@push('page_scripts')
<!-- Plugins js -->
<script src="{{ asset('/themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/js/pages/form-masks.init.js') }}"></script>

<script src="{{ asset('/themes/admin/assets/libs/autocomplete/jquery.autocomplete.min.js') }}"></script>
<script type="text/javascript">
    $(function (){
        var main_category = $.map({!! $main_category !!}, function (a, e){
            return {value: a, data: e};
        });

        $("#main_category").autocomplete( {
            lookup: main_category, lookupFilter:function(a, e, n) {
                return new RegExp("\\b"+$.Autocomplete.utils.escapeRegExChars(n), "gi").test(a.value)
            },
            onSelect:function(a) {
                $("#main_category_id_h").val(a.data);
                get_service_category();
            }
        });

        $("#service_category_type_id").on("change", function (){
            get_service_category();
        });
        
        
        $(".additional_phone").on("change", function (){
            var phone_value = $(this).val();

            if (phone_value == "yes"){
                $("#additional_phone_number").show();
                $("#additional_phone_number #additional_phone_number_field").attr("required", true);
            } else {
                $("#additional_phone_number").hide();
                $("#additional_phone_number #additional_phone_number_field").attr("required", false);
            }
        });


        @if(isset($formObj))
        get_service_category();
        @endif
    });

    function get_service_category (){
        var main_category_id = $("#main_category_id_h").val();
        var service_category_type_id = $("#service_category_type_id").val();

        if (typeof main_category_id !== 'undefined' && main_category_id != '' && typeof service_category_type_id !== 'undefined' && service_category_type_id != ''){
            $.ajax({
                url: '{{ url("admin/leads/get-service-categories") }}',
                type: 'POST',
                data: {'main_category_id': main_category_id, 'service_category_type_id' : service_category_type_id, '_token': '{{ csrf_token() }}'},
                success: function (records){
                    if (typeof records.success !== 'undefined'){

                    } else {
                        var service_category = $.map(records, function (a, e){
                            return {value: a, data: e};
                        });

                        $("#service_category").autocomplete( {
                            lookup: service_category, lookupFilter:function(a, e, n) {
                                return new RegExp("\\b"+$.Autocomplete.utils.escapeRegExChars(n), "gi").test(a.value)
                            },
                            onSelect:function(a) {
                                $("#service_category_id_h").val(a.data);
                            }
                        });
                    }
                    
                }
            });
        }
    }
</script>
@endpush