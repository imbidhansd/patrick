@extends('company.layout-without-menu-and-sidebar')

@section ('content')
@section('title', $admin_page_title)
@include('flash::message')
@include('admin.includes.formErrors')

{!! Form::open(['url' => 'phone-in-lead','id' => 'form_phone_in_lead', 'class' => 'module_form', 'files' => true]) !!}
<div class="card-box">
    <h1 class="text-center">{{ $admin_page_title }}</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Lead source<span class="required">*</span></label>
                {!! Form::select('lead_source', $affiliates, null, ['id' => 'lead_source', 'class' => 'form-control custom-select',
                'required' => true, 'maxlength' => 255,
                'placeholder' => 'Select', 
                'data-parsley-trigger' => 'blur']) !!}
            </div>
        </div>
        
    </div>
    <div class="row">
    <div class="col-md-12">
            <div class="form-group">
                <label>Service Category Type<span class="required">*</span></label>
                {!! Form::select('service_category_type', $service_category_type, null, ['id' => 'service_category_type', 'class' => 'form-control custom-select',
                'required' => true, 'maxlength' => 255,
                'placeholder' => 'Select', 
                'data-parsley-trigger' => 'blur']) !!}
            </div>
        </div> 
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Main Category<span class="required">*</span></label>
                {!! Form::select('main_category', [], null, ['id'=>'main_category','class' => 'form-control custom-select',
                'required' => true, 'maxlength' => 255,
                'placeholder' => 'Select', 
                'data-parsley-trigger' => 'blur']) !!}
            </div>
        </div> 
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Service Category<span class="required">*</span></label>
                {!! Form::select('service_category', [], null, ['id'=>'service_category', 'class' => 'form-control custom-select',
                'required' => true, 'maxlength' => 255,
                'placeholder' => 'Select', 
                'data-parsley-trigger' => 'blur']) !!}
            </div>
        </div>
    </div> 
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Full Name <span class="required">*</span></label>
                {!! Form::text('name', null, ['id' => 'name',  'class' => 'form-control', 'placeholder' => '', 'required' => true, 'maxlength' => 255, 'data-parsley-trigger' => 'blur']) !!}
            </div>
        </div>  
        <div class="col-md-4">
            <div class="form-group">
                <label>Email Address <span class="required">*</span></label>
                {!! Form::email('email', null, ['id' => 'email', 'class' => 'form-control', 'placeholder' =>
                '', 'required' => true, 'maxlength' => 255, 'data-parsley-trigger' => 'blur']) !!}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label>Phone <span class="required">*</span></label>
                {!! Form::text('phone', null, ['id' => 'phone', 'class' => 'form-control', 'placeholder' => '', 'required' => true, 'maxlength' => 255, 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000', 'data-parsley-trigger' => 'blur']) !!}
            </div>
        </div>          
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Address <span class="required">*</span></label>
                {!! Form::text('address', null, [
                'id' => 'address',
                'class' => 'form-control', 
                'placeholder' => '', 
                'required' => true, 
                'maxlength' => 255, 
                'data-parsley-trigger' => 'blur']) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
            <label>Time Frame<span class="required">*</span></label>
                {!! Form::select('timeframe', $timeframe, null, ['id' => 'timeframe',
                'class' => 'form-control custom-select',
                'required' => true, 'maxlength' => 255,
                'placeholder' => 'Select', 
                'data-parsley-trigger' => 'blur']) !!}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
            <label>State<span class="required">*</span></label>
                {!! Form::select('state', $states, null, ['id' => 'state',
                'class' => 'form-control custom-select',
                'required' => true, 'maxlength' => 255,
                'placeholder' => 'Select', 
                'data-parsley-trigger' => 'blur']) !!}
            </div>
        </div> 
        <div class="col-md-4">
            <div class="form-group">
                <label>City <span class="required">*</span></label>
                {!! Form::text('city', null, ['id' => 'city',
                'class' => 'form-control', 
                'placeholder' => '', 
                'required' => true,
                 'maxlength' => 255, 
                 'data-parsley-trigger' => 'blur']) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Zipcode <span class="required">*</span></label>
                {!! Form::text('zip', null, ['id' => 'zip',
                'class' => 'form-control', 
                'placeholder' => '', 
                'required' => true, 
                'maxlength' => 255, 
                'data-parsley-trigger' => 'blur']) !!}
            </div>
        </div>  
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Additional Info <span class="required">*</span></label>
                {!! Form::textarea('additional_info', null, [
                    'id' => 'additional_info',
                    'class' => 'form-control',
                    'required' => true,
                    'maxlength' => 255,
                    'rows' => 3,
                    'data-parsley-trigger' => 'blur'
                ]) !!}
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-info float-md-right last_input step1_submit btn-bg-default generate_lead_btn">Submit</button>
</div>
{!! Form::close() !!}

<style>
   .form-group label {
    padding: 0;
    margin: 0 0 5px 0;
    font-size: 14px;
    color: #003e74;
    font-weight: 600;
}
</style>
@endsection

@section ('page_js')

<!-- Slick -->

<link rel="stylesheet" type="text/css" href="{{ asset('thirdparty/slick/slick.css') }}">
<script src="{{ asset('thirdparty/slick/slick.js') }}" type="text/javascript" charset="utf-8"></script>

<!-- Plugins js -->
<script src="{{ asset('/themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>

<!-- Init js-->
<script src="{{ asset('/themes/admin/assets/js/pages/form-masks.init.js') }}"></script>

<script>
$(document).on("change", "#service_category_type", function () {
    var service_category_type = $(this).val();
    var source = $("#lead_source").val();
    var sendData = {
        'service_category_type': service_category_type,
        'source': source,
        '_token': '{{ csrf_token() }}'
    };
    on_service_category_type_selection(sendData);
});

$(document).on("change", "#main_category", function () {
    var main_category = $(this).val();
    var service_category_type = $("#service_category_type").val();
    var source = $("#lead_source").val();
    var sendData = {
        'service_category_type': service_category_type,
        'main_category': main_category,
        'source': source,
        '_token': '{{ csrf_token() }}'
    };
    on_main_category_selection(sendData);
});

function on_service_category_type_selection(ajaxCallData)
{
    $.ajax({
        url: '{{ url("phone-in-lead/get-maincategories") }}',
        type: 'POST',
        data: ajaxCallData,
        success: function (data) {
            if (typeof data.success !== 'undefined' && data.success == 1) {
                var selectDropdown = $('#main_category');
                selectDropdown.empty();
                selectDropdown.append($('<option>', {
                    value: '',
                    text: 'Select'
                }));
                $.each(data.data, function(index, item) {
                selectDropdown.append($('<option>', {
                        value: item.id,
                        text: item.title
                    }));
                });
            } 
        }
    });
}

function on_main_category_selection(ajaxCallData)
{
    $.ajax({
        url: '{{ url("phone-in-lead/get-servicecategories") }}',
        type: 'POST',
        data: ajaxCallData,
        success: function (data) {
            if (typeof data.success !== 'undefined' && data.success == 1) {
                var selectDropdown = $('#service_category');
                selectDropdown.empty();
                selectDropdown.append($('<option>', {
                    value: '',
                    text: 'Select'
                }));
                $.each(data.data, function(index, item) {
                selectDropdown.append($('<option>', {
                        value: item.id,
                        text: item.title
                    }));
                });
            } 
        }
    });
}

$(document).on("submit", "#form_phone_in_lead", function () {

    var form_url = $(this).attr("action");

    $(".generate_lead_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
    $(".generate_lead_btn").attr('disabled', true);

    $("html").css({
        'background': 'url({{ asset("/images/find_a_pro/processing.gif") }}) no-repeat center center fixed #fff',
        '-webkit-background-size': '50%',
        '-moz-background-size': '50%',
        '-o-background-size': '50%',
        'background-size': '50%',
        'height': '100%'
    });
    $("body").css({
        'opacity': '0'
    });

    $.ajax({
        url: form_url,
        type: 'POST',
        data: $("#form_phone_in_lead").serialize(),
        success: function (data) {
            setTimeout(function(){ 
                $("html").css({
                    'background-image': 'none'
                });

                $("body").css({
                    'opacity': '1'
                });

                $(".generate_lead_btn").html('Submit Request');
                $(".generate_lead_btn").attr('disabled', false);

                Swal.fire({
                    title: data.title,
                    type: data.type,
                    html: data.message,
                    confirmButtonText: "Ok",
                }).then(function (t) {
                    if (data.success == 1) {
                        window.location.href = window.location.href;
                    }                    
                });

            }, 9000);
        }
    });
    return false;
});
/**Trusted Form Certificate */
(function() {
    var field = "cert_url";
    var provideReferrer = false;
    var invertFieldSensitivity = false;
    var tf = document.createElement("script");
    tf.type = "text/javascript";
    tf.async = true;
    tf.src = "http" + ("https:" == document.location.protocol ? "s" : "") +
        "://api.trustedform.com/trustedform.js?provide_referrer=" + escape(provideReferrer) + "&field=" + escape(
            field) + "&l=" + new Date().getTime() + Math.random() + "&invert_field_sensitivity=" +
        invertFieldSensitivity;
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(tf, s);
})();

function initAutocomplete() {
        //Bill Address autocomplete
        let autocompleteAddressField = document.querySelector("#address");                       
        autocompleteAddress = new google.maps.places.Autocomplete(autocompleteAddressField, {
            componentRestrictions: { country: ["us"] },
            fields: ["address_components"],
            types: ["address"],
        });
        autocompleteAddressField.focus();          
        autocompleteAddress.addListener("place_changed", fillInAddress);        
}
function fillInAddress() {
    const place = autocompleteAddress.getPlace(); 

    for (const component of place.address_components) {
        const componentType = component.types[0];
        switch (componentType) {    
            case "postal_code": {
                document.querySelector("#zip").value = component.long_name;
                break;
            }
            case "locality": {
                document.querySelector("#city").value = component.long_name;
                break;
            }                        
            case "administrative_area_level_1": {
                selectedState = component.long_name;
                var autocompleteStateElement = document.getElementById('state');
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
}
window.initAutocomplete = initAutocomplete;  
</script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{ env('GOOGLE_MAP_API_KEY') }}&callback=initAutocomplete&libraries=places&v=weekly"></script>
@stack('page_scripts')

@endsection
