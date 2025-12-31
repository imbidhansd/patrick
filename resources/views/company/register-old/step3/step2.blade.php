<h4>Category Listings</h4>
<h5>Please choose the main category of services you provide : *</h5>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="main_category">Main Category : *</label>
            {!! Form::select('main_category_id', [], null, ['id' => 'main_category_id','class' =>
            'form-control custom-select last_input',
            'required' => true, 'placeholder' => 'Select']) !!}

        </div>
    </div>
</div>

<div class="card">
    <div class="card-body main_service_category_container">
    </div>
</div>

<div class="clearfix">&nbsp;</div>
<button type="button" data-step="1" class="btn btn-dark float-md-left back-btn">Back</button>
<button type="button" data-step="1"
    class="btn btn-info float-md-right next_btn_3_2 current_step_submit_btn">Next</button>
