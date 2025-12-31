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
            <label>Please enter the main zip code of your working territory.: *</label>
            {!! Form::text('main_zipcode', $main_zipcode, ['id' => 'zipcode', 'required' => true, 'class' =>
            'form-control', 'data-toggle'
            =>
            'input-mask',
            'data-mask-format' => '00000']) !!}
        </div>
    </div>
</div>

<button type="button" class="btn btn-md btn-primary show_map">Next &nbsp; <i class="fa fa-angle-right"></i></button>

<div class="map_section ">
    @include ('company.register.step3._google_map')
</div>

<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>

<button type="button" data-step="3.5" class="btn btn-dark float-md-left back-btn mb-3">Back</button>

<div class="text-right">
    <div class="checkbox checkbox-primary">
        <input id="terms" type="checkbox" required>
        <label for="terms">I agree to Free Preview Trial <a href="#">Trems & Conditions</a></label>
    </div>

    <div class="clearfix">&nbsp;</div>

    <button class="btn btn-md btn-primary current_step_submit_btn" type="submit">
        <i class="fa fa-save"></i> &nbsp; Submit Free Preview Trial
    </button>
</div>
