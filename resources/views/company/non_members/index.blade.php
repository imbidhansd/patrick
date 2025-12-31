@extends('company.layout-without-sidebar')

@section ('content')

<?php /* @include('admin.includes.breadcrumb') */ ?>
@include('admin.includes.formErrors')
@include('flash::message')

<!-- Basic Form Wizard -->

<div class="card-box">
    <div class="row">
        <div class="col-xl-8 offset-xl-2 text-center">
            <h2>Get Your Company Listed With {{ env('APP_NAME') }}!</h2>
            <div class="clearfix">&nbsp;</div>
            <h5 class="text-primary mb-4">Are You An Experienced Pro Who Provides Quality Services?</h5>
            <p>
                <?php /* Tens of thousands of */ ?>
                Consumers Trust {{ env('APP_NAME') }} to help them find honest and reliable companies.
                <br />
                Learn how to get your company listed as an Official {{ env('APP_NAME') }} Recommended Company and get connected with them.
            </p>
            <?php /* <p>Become an Official TrustPatrick.com Recommended Company and connect with them.</p> */ ?>
            <p>Register below to learn more!</p>
        </div>
    </div>

    <div class="clearfix">&nbsp;</div>
    <div class="clearfix">&nbsp;</div>

    <div class="row">
        <div class="col-xl-8 offset-xl-2">
            {!! Form::open(['class' => 'module_form', 'id' => 'get_listed_form']) !!}
            {!! Form::hidden('re_register', 'no', ['id' => 're_register'])!!}
            <div class="row">
                <div class="col-xl-6">
                    <div class="form-group">
                        {!! Form::label('First Name') !!}
                        {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => 'First Name', 'required' => true]) !!}
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="form-group">
                        {!! Form::label('Last Name') !!}
                        {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'Last Name', 'required' => true]) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6">
                    <div class="form-group">
                        {!! Form::label('Email') !!}
                        {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'email', 'placeholder' => 'Email', 'required' => true]) !!}
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="form-group">
                        {!! Form::label('Company Name') !!}
                        {!! Form::text('company_name', null, ['class' => 'form-control', 'placeholder' => 'Company Name', 'required' => true]) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6">
                    <div class="form-group">
                        {!! Form::label('Phone') !!}
                        {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => 'Phone', 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000', 'required' => true]) !!}
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="form-group">
                        {!! Form::label('Address') !!}
                        {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'Address', 'required' => true, 'id' => 'autocomplete_address']) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6">
                    <div class="form-group">
                        {!! Form::label('City') !!}
                        {!! Form::text('city', null, ['class' => 'form-control', 'placeholder' => 'City', 'required' => true, 'id' => 'autocomplete_city']) !!}
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="form-group">
                        {!! Form::label('State') !!}
                        {!! Form::select('state_id', $states, null, ['class' => 'form-control custom-select', 'placeholder' => 'State', 'required' => true, 'id' => 'autocomplete_state']) !!}
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="form-group">
                        {!! Form::label('zipcode') !!}
                        {!! Form::text('zipcode', null, ['class' => 'form-control', 'placeholder' => 'Zipcode', 'data-toggle' => 'input-mask', 'data-mask-format' => '00000', 'required' => true, 'id' => 'autocomplete_zipcode']) !!}
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="form-group">
                        {!! Form::label('Mile Range (How far do you travel from your Home/Office)') !!}
                        {!! Form::select ('mile_range', config('config.mile_options'), null, ['id' => 'mile_range' ,'class' => 'form-control custom-select', 'placeholder' => 'Select Mile Range', 'required' => true]) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 hide">
                    <div class="form-group">
                        {!! Form::label('Type of service provider') !!}
                        {!! Form::select('trade_id', $trades, null, ['class' => 'form-control custom-select', 'id' => 'trade_id', 'placeholder' => 'Select Trade', 'required' => true]) !!}
                    </div>
                </div>
                
                <div class="col-xl-12" id="top_level_category_selection">
                    <div class="form-group">
                        {!! Form::label('Service Offered (Please select all that apply)') !!}
                        {!! Form::select('top_level_categories[]', [], null, ['class' => 'form-control custom-select select2', 'id' => 'top_level_categories', 'multiple' => true, 'required' => true]) !!}
                    </div>
                </div>

                @if (isset($service_category_types) && count($service_category_types) > 0)
                <div class="col-xl-12" id="service_category_type">
                    <div class="form-group">
                        {!! Form::label('Service Type') !!}
                        <select name="service_category_type_id" id="service_category_type_id" class="form-control custom-select">
                            <option value="">Select Service Type</option>
                            @foreach ($service_category_types AS $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                            <option value="both">Both</option>
                        </select>
                    </div>
                </div>
                @endif
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="form-group">
                        {!! Form::label('How did you hear about us?') !!}
                        {!! Form::select('how_did_you_hear_about_us', $how_did_you_hear_about_us, null, ['class' => 'form-control custom-select', 'placeholder' => 'How did you hear about us?', 'required' => true]) !!}
                    </div>
                </div>

                <div class="col-xl-12">
                    <div class="form-group">
                        {!! Form::label('Comments/Questions') !!}
                        {!! Form::textarea('comments', null, ['class' => 'form-control', 'placeholder' => 'Comments/Questions', 'required' => false]) !!}
                    </div>
                </div>
            </div>

            <div class="text-center">
                <div class="g-recaptcha" id="get_listed-recaptcha" data-callback="imNotARobot" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY')  }}"></div>
                
                <button type="submit" class="btn btn-primary btn-md" id="get_listed_submit_btn" disabled>Submit</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@section ('page_js')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<!-- Plugins js -->
<script src="{{ asset('/themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>

<!-- Init js-->
<script src="{{ asset('/themes/admin/assets/js/pages/form-masks.init.js') }}"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>

<script type="text/javascript">
    var imNotARobot = function() {
        //console.info("Button was clicked");
        $("#get_listed_submit_btn").attr("disabled", false);
    };
    $(function () {
        $('.select2').select2();
        
        $("#trade_id").on("change", function (){
            var trade_id = $(this).val();
            
            if (typeof trade_id !== 'undefined' && trade_id != ''){
                if (trade_id == 1){
                    $("#service_category_type").show();
                    $("#service_category_type #service_category_type_id").attr("required", true);
                } else {
                    $("#service_category_type").hide();
                    $("#service_category_type #service_category_type_id").attr("required", false);
                }
                
                $.ajax({
                    url: '{{ url("get-listed/get-top-level-categories") }}',
                    type: 'POST',
                    data: {'trade_id': trade_id, '_token': '{{ csrf_token() }}'},
                    success: function (data){
                        if (typeof data.success !== 'undefined'){
                            Swal.fire({
                                title: data.title,
                                type: data.type,
                                text: data.message
                            });
                        } else {
                            $("#top_level_categories").html(data);
                        }
                    }
                });
            }
        });
        
        $('#trade_id').val(1);
        $('#trade_id').trigger('change');
        
        $("#get_listed_form").on("submit", function (){
            var formData = $(this).serialize();
            
            var instance = $(this).parsley();
            if (instance.isValid()){
                $("#get_listed_submit_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
                $("#get_listed_submit_btn").attr('disabled', true);
            } else {
                $("#get_listed_submit_btn").html('Submit');
                $("#get_listed_submit_btn").attr('disabled', false);
            }
            
            
            $.ajax({
                url: '{{ url("get-listed") }}',
                type: 'POST',
                data: formData,
                success: function (data) {
                    $("#get_listed_submit_btn").html('Submit');
                    $("#get_listed_submit_btn").attr('disabled', false);
                    
                    if (data.success == '2'){
                        Swal.fire({
                            title: data.title,
                            type: data.type,
                            html: data.message,
                            showCancelButton: !0,
                            cancelButtonText: "Change Email",
                            cancelButtonColor: "#ff0000",
                            confirmButtonText: "Re-Register",
                            confirmButtonColor: "#003E74",
                        }).then(function (t) {
                            if (typeof t.value != 'undefined'){
                                $("#re_register").val('yes');
                                $("#get_listed_form").submit();
                            } else {
                                $("#email").addClass('error');
                                $("#email").val('');
                                $("#email").focus();
                                
                                return false;
                            }
                        });
                    } else {
                        Swal.fire({
                            title: data.title,
                            type: data.type,
                            html: data.message
                        }).then(function (t) {
                            //window.location.reload();
                            window.location.href = '{{ url("get-listed") }}';
                        });
                    }
                }
            });
            
            return false;
        });
    });
    function initAutocomplete() {
        let address1Field = document.querySelector("#autocomplete_address");                       
        autocomplete = new google.maps.places.Autocomplete(address1Field, {
            componentRestrictions: { country: ["us"] },
            fields: ["address_components"],
            types: ["address"],
        });
        address1Field.focus();          
        autocomplete.addListener("place_changed", fillInAddress);
    }
    function fillInAddress() {
        const place = autocomplete.getPlace(); 

        for (const component of place.address_components) {
            const componentType = component.types[0];
            switch (componentType) {    
                case "postal_code": {                            
                    document.querySelector("#autocomplete_zipcode").value = component.long_name;
                    break;
                }                
                case "locality": {
                    document.querySelector("#autocomplete_city").value = component.long_name;
                    break;
                }                        
                case "administrative_area_level_1": {
                    selectedState = component.long_name;
                    var autocompleteStateElement = document.getElementById('autocomplete_state');
                    var autocompleteStateOptions = autocompleteStateElement.options;

                    for (var i = 0; i < autocompleteStateOptions.length; i++) {
                        if (autocompleteStateOptions[i].text.toLowerCase() === selectedState.toLowerCase()) {
                            autocompleteStateOptions[i].selected = true;
                            break;
                        }
                    }
                    break;
                }
            }
        }           
        
        document.querySelector("#autocomplete_address").focus();
    }

    window.initAutocomplete = initAutocomplete;
</script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{ env('GOOGLE_MAP_API_KEY') }}&callback=initAutocomplete&libraries=places&v=weekly"></script>
@endsection