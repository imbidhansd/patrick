<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control max', 'placeholder' => 'Enter Title', 'required' =>
            true, 'maxlength' => 255]) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('TLC Id') !!}
            {!! Form::text('tlc_id', ($new_form) ? $tlc_max_id : null, ['class' => 'form-control max', 'placeholder' => 'Enter TLC Id', 'required'
            => true, 'maxlength' => 5, 'data-parsley-type' => 'integer']) !!}
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label>Trades:</label>
            {!! Form::select('trades[]', $trades, is_object($top_level_category_trades) ?
            $top_level_category_trades->toArray() : null, ['class' => 'form-control select2', 'multiple' =>
            'multiple']) !!}
        </div>
    </div>
</div>
<hr />
<div class="row">    
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Status') !!}
            {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' => 'form-control custom-select', 'required' => true]) !!}
        </div>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-12">
        <h4>Top Search</h4>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('Available for Top Search?') !!}
            {!! Form::select('top_search_status', ['yes' => 'Yes', 'no' => 'No'], null, ['class' => 'form-control custom-select', 'required' => false]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('Image') !!}
            {!! Form::text('top_search_image', null, ['class' => 'form-control', 'placeholder' => 'Enter Image Link', 'required' => false]) !!}
        </div>
    </div>
    
    @if(isset($formObj))
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('Sort Order') !!}
            {!! Form::text('top_search_sort_order', null, ['class' => 'form-control', 'placeholder' => 'Enter Order', 'required' => false]) !!}
        </div>
    </div>
    @endif
</div>



<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>
