{!! Form::open(['url' => 'register/step7','id' => 'step7_form', 'class' => 'module_form', 'files' => true]) !!}
<h5>Zipcode Radius</h5>

<?php
    $main_zipcode = null;
    $mile_range = null;

    if (isset($userObj) && !is_null($userObj)){
        $main_zipcode = $userObj->main_zipcode;
        $mile_range = $userObj->mile_range;
    }
?>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Please enter the main zip code of your working territory: <span class="required">*</span></label>
            {!! Form::text('main_zipcode', $main_zipcode, ['id' => 'zipcode', 'required' => true, 'class' =>
            'form-control', 'data-toggle'
            =>
            'input-mask',
            'data-mask-format' => '00000',
            'data-parsley-trigger' => 'blur']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="miles_selection">Please select a radius: <span class="required">*</span></label>
            {!! Form::select ('mile_range', config('config.mile_options'), isset($mile_range) ? $mile_range : null,
            ['id' =>
            'mile_range' ,'class' => 'form-control custom-select',
            'placeholder' => 'Select Zip radius',
            'required' => false] )
            !!}
        </div>
    </div>
</div>

<div class="map_section ">



    <div class="alert alert-info alert-dismissible miles-alert d-none" role="alert">
        <i class="fas fa-info-circle text-dark mr-2"></i>
        <span>All ZIP codes within a <span class="selected_miles">50</span> mile radius have been selected. Editing the zip code radius and adding or removing individual zip codes can be done once your registration is complete.</span>
    </div>

    <?php /*
    <div class="alert alert-info alert-dismissible miles-alert d-none" role="alert">
        <i class="fas fa-info-circle text-dark mr-2"></i>
        <span>All zip codes within a <span class="selected_miles">50</span> mile radius of have been
        selected and will be displayed on your Company Profile Page.</span>
    </div>

    <div class="alert alert-info alert-dismissible" role="alert">
        <i class="fas fa-info-circle text-dark mr-2"></i>
        <span>Editing zip code radius and adding or removing individual zip codes can be done from your
        company dashboard once registration is complete.</span>
    </div>
    */ ?>
    <div class="clearfix">&nbsp;</div>

    <div class="googlemapborder">
        <div id="map-canvas" style="height:300px;"></div>
    </div>

</div>

<div class="clearfix">&nbsp;</div>

<div class="row">
    <div class="col-md-12 col-sm-12 text-right">
        <div class="checkbox checkbox-primary ">
            <input id="terms" type="checkbox" required data-parsley-errors-container="#step7-terms-error-container">
            <label for="terms">
                I agree to <a href="javascript:;" data-toggle="modal" data-target="#termsModal">Terms Of Use</a>
                <div class="clearfix"></div>
                <div id="step7-terms-error-container" style="float: left;"></div>
            </label>
        </div>
        
        <div class="g-recaptcha" id="register-recaptcha" data-callback="imNotARobot" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY')  }}" style="float: right;"></div>
    </div>
</div>

<div class="clearfix">&nbsp;</div>

<div class="row">
    <div class="col-md-6 col-sm-6 col-6">
        <button type="button" class="btn btn-dark float-md-left map-back-btn">Back</button>
    </div>

    <div class="col-md-6 col-sm-6 col-6 text-right">
        <button class="btn btn-md btn-primary float-right current_step_submit_btn btn-bg-default" type="submit" disabled>
            <i class="fa fa-save"></i> &nbsp; Submit <?php /* Preview Trial */ ?>
        </button>
    </div>
</div>

<div class="text-right">
</div>
<div class="clearfix"></div>
<?php /* {!! Form::hidden('recaptcha', null, ['id' => 'recaptcha']) !!} */ ?>
{!! Form::close() !!}


@push('page_scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php /* @include ('admin.includes.captcha_js', ['action_field' => 'registration'])  */ ?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{ env('GOOGLE_MAP_API_KEY') }}&callback=initAutocomplete&libraries=places&v=weekly"></script>
<script src="{{ asset('js/zipcode-radius.js') }}"></script>

<script type="text/javascript">
    var imNotARobot = function() {
        //console.info("Button was clicked");
        $(".current_step_submit_btn").attr("disabled", false);
    };
    
    $(function(){
        $('.map-back-btn').click(function(){
            if ($('#secondary_main_category_id option:checked').text() == 'None') {
                // Move to Step 5
                slick_slide_to(4); // 4 === Slide 5 (Secondary Main Category Slide)
                return false;
            }
            if ($('#main_category_id option').length == 2){
                slick_slide_to(3); // 3 === Slide 4 (Main Category Slide)
                return false;
            }else if ($('#main_category_id option').length == 3){
                slick_slide_to(4); // 4 === Slide 5 (Secondary Main Category Slide)
                return false;
            }

            $(".register-steps").slick('slickPrev');
        });

        $('#zipcode').change(function(){
            var zipcode = parseInt($(this).val());
            if (!(zipcode >= 10000 && zipcode <= 99999)) {
                Swal.fire(
                    'Warning',
                    'Please enter valid US postcode',
                    'warning'
                );
            } else {
                getGoogleMaps(1);
            }
        });

        $('#mile_range').change(function() {
            if ($(this).val() > 0) {
                getGoogleMaps($(this).val());
                $('.miles-alert').removeClass('d-none');
                $('.selected_miles').html($(this).val());
            } else {
                getGoogleMaps(1);
                $('.miles-alert').addClass('d-none');
            }
            refresh_slick_content();
        });
        
        
        $("#step7_form").on("submit", function (){
            var instance = $(this).parsley();
            if (instance.isValid()){
                $(this).find(".current_step_submit_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
                $(this).find(".current_step_submit_btn").attr('disabled', true);
            } else {
                $(this).find(".current_step_submit_btn").html('<i class="fa fa-save"></i> &nbsp; Submit');
                $(this).find(".current_step_submit_btn").attr('disabled', false);
            }
        });

    });

</script>

@endpush
