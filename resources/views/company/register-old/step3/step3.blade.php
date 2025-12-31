<h4>Category Listings</h4>
<h5>Please list a secondary category of services you provide:</h5>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="main_category">Secondary Category : *</label>

            {!! Form::select('secondary_main_category_id', [], null, ['id' => 'secondary_main_category_id','class' =>
            'form-control custom_select last_input',
            'required' => false, 'placeholder' => 'Select']) !!}

        </div>
    </div>
</div>

<div class="card">
    <div class="card-body secondary_service_category_container"></div>
</div>

<div class="clearfix">&nbsp;</div>
<button type="button" class="btn btn-dark float-md-left back-btn">Back</button>
<button type="button" class="btn btn-info float-md-right next_btn_3_3 current_step_submit_btn">Next</button>
