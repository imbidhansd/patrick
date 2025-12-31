<h5>Zipcode Radius</h5>



<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Please enter the main zip code of your working territory.: *</label>
            {!! Form::text('main_zipcode', null, ['id' => 'zipcode', 'class' => 'form-control', 'data-toggle' =>
            'input-mask',
            'data-mask-format' => '00000']) !!}
        </div>
    </div>
</div>

<button type="button" class="btn btn-md btn-primary show_map">Next &nbsp; <i class="fa fa-angle-right"></i></button>

<div class="map_section ">
    @include ('company.register.free-preview-trial.step3._google_map')
</div>

<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>


<button type="button" data-step="3.5" class="btn btn-dark float-md-left back-btn">Back</button>

<div class="text-right">

    <div class="checkbox checkbox-primary">
        <input id="terms" type="checkbox">
        <label for="terms">I agree to Free Preview Trial <a href="#">Trems & Conditions</a></label>
    </div>

    <div class="clearfix">&nbsp;</div>

    <button class="btn btn-md btn-primary">
        <i class="fa fa-save"></i> &nbsp; Submit Free Preview Trial
    </button>
</div>
