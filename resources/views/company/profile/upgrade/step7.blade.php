{!! Form::open(['url' => 'account/upgrade/step7','id' => 'step7_form', 'class' => 'module_form upgrade_step7_form', 'files' => true]) !!}
<h5>Zipcode Radius</h5>

<?php
$main_zipcode = null;
$mile_range = null;

if (isset($companyObj) && !is_null($companyObj)) {
    $main_zipcode = $companyObj->main_zipcode;
    $mile_range = $companyObj->mile_range;
}
?>

<div class="row">
    <div class="col-lg-6 col-md-8">
        <div class="form-group">
            <label>Please enter the main zip code of your working territory: <span class="required">*</span></label>
            {!! Form::text('main_zipcode', $main_zipcode, ['id' => 'zipcode', 'required' => true, 'class' =>
            'form-control', 'data-toggle'
            =>
            'input-mask',
            'data-mask-format' => '00000']) !!}
        </div>
    </div>
    <div class="col-lg-6 col-md-4">
        <div class="form-group">
            <label for="miles_selection">Please select a radius: <span class="required">*</span> <br /></label>
            {!! Form::select ('mile_range', config('config.mile_options'), isset($mile_range) ? $mile_range : null,
            ['id' =>
            'mile_range' ,'class' => 'form-control custom-select',
            'placeholder' => 'Select Zip radius',
            'required' => false] )
            !!}
        </div>
    </div>
</div>

<div class="map_section mt-0">
    <div class="googlemapborder">
        <div id="map-canvas" style="height:300px;"></div>
    </div>

</div>

<div class="clearfix">&nbsp;</div>
<button type="button" class="btn btn-dark float-md-left map-back-btn">Back</button>
<button type="submit" class="btn btn-md btn-primary float-md-right current_step_submit_btn"><i class="fa fa-save"></i> &nbsp; Submit</button>

<?php /* <div class="row">
    <div class="col-md-6 col-sm-6">
        <button type="button" class="btn btn-dark float-md-left map-back-btn">Back</button>
    </div>

    <div class="col-md-6 col-sm-6 text-right">
        <button class="btn btn-md btn-primary float-right current_step_submit_btn" type="submit">
            <i class="fa fa-save"></i> &nbsp; Submit
        </button>
    </div>
</div> */ ?>
<div class="text-right">

</div>
<div class="clearfix"></div>

{!! Form::close() !!}

@push('page_scripts')

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{ env('GOOGLE_MAP_API_KEY') }}"></script>
<script src="{{ asset('js/zipcode-radius.js') }}"></script>

<script type="text/javascript">
$(function () {

    $('.map-back-btn').click(function () {
        if ($('#secondary_main_category_id option:checked').text() == 'None') {
            // Move to Step 5
            slick_slide_to(4); // 4 === Slide 5 (Secondary Main Category Slide)
            return false;
        }
        if ($('#main_category_id option').length == 2) {
            slick_slide_to(3); // 3 === Slide 4 (Main Category Slide)
            return false;
        } else if ($('#main_category_id option').length == 3) {
            slick_slide_to(4); // 4 === Slide 5 (Secondary Main Category Slide)
            return false;
        }

        $(".register-steps").slick('slickPrev');
    });

    $('#zipcode').change(function () {
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

    $('#mile_range').change(function () {
        if ($(this).val() > 0) {
            getGoogleMaps($(this).val());
            $('.miles-alert').removeClass('d-none');
            $('.selected_miles').html($(this).val());
        } else {
            getGoogleMaps(1);
            $('.miles-alert').addClass('d-none');
        }
    });

    $(".upgrade_step7_form").on("submit", function () {
        var instance = $(this).parsley();
        if (instance.isValid()) {
            $(".current_step_submit_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
            $(".current_step_submit_btn").attr('disabled', true);
        } else {
            $(".current_step_submit_btn").html('<i class="fa fa-save"></i> &nbsp; Submit');
            $(".current_step_submit_btn").attr('disabled', false);
        }
    });

});

</script>

@endpush
