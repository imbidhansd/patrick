<h5>Categories</h5>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Please select type of business*') !!}
            {!! Form::select('trade_id', $trades, null, ['id' => 'trade_id','class' => 'form-control custom-select
            last_input',
            'required' => true, 'placeholder' => 'Select']) !!}
        </div>
    </div>
    <div class="col-md-12 top_level_categories_container">
    </div>
</div>


<div class="clearfix">&nbsp;</div>
<button type="button" class="btn btn-dark float-md-left back-btn">Back</button>
<button type="button" data-step="1" data-step="2"
    class="btn btn-info float-md-right next_btn_3_1 current_step_submit_btn">Next</button>
