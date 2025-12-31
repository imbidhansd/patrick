<h5>Categories</h5>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Please select type of business*') !!}
            {!! Form::select('trade_id', $trades, null, ['id' => 'trade_id','class' => 'form-control',
            'required' => true, 'placeholder' => 'Select']) !!}
        </div>
    </div>

    <div class="col-md-12 top_level_categories_container">


    </div>


</div>
