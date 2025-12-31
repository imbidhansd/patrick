<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="miles_selection">Please select a radius : *</label>

            {!! Form::select ('mile_range', config('config.mile_options'), isset($mile_range) ? $mile_range : null,
            ['id' =>
            'mile_range' ,'class' => 'form-control custom-select',
            'placeholder' => 'Select Zip radius',
            'required' => true] )
            !!}

        </div>
    </div>

    <div class="col-md-9 text-info">
        <p>All zip codes within a <span class="selected_miles">50</span> mile radius of have been
            selected and will be displayed on your Company Profile Page.</p>
        <p>Editing zip code radius and adding or removing individual zip codes can be done from your
            company dashboard once registration is complete.</p>
    </div>
</div>

<div class="clearfix">&nbsp;</div>

<div class="googlemapborder">
    <div id="map-canvas" style="height:300px;"></div>
</div>
