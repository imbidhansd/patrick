<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Trade') !!}
            {!! Form::select('trade_id', $trades, null, ['class' => 'form-control custom-select', 'placeholder' => 'Select Trade', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter Title', 'required' => true]) !!}
        </div>
    </div>
</div>

<hr />

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Status') !!}
            <div class="select">
                {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' => 'form-control custom-select', 'required' => true]) !!}
            </div>
        </div>
    </div>
</div>

<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>
