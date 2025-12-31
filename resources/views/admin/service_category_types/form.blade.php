<div class="row">
    <?php /* <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Trade') !!}
            {!! Form::select('trade_id', $trades, null, ['class' => 'form-control custom-select', 'placeholder' => 'Select Trade', 'required' => true]) !!}
        </div>
    </div> */ ?>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control max', 'placeholder' => 'Enter Title', 'required' =>
            true, 'maxlength' => 255]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Abbreviation') !!}
            {!! Form::text('abbr', null, ['class' => 'form-control max', 'placeholder' => 'Enter Abbreviation',
            'required' =>
            false, 'maxlength' => 20]) !!}
        </div>
    </div>
    
    <div class="col-md-6">
        @include ('admin.includes._img_field', ['label' => 'Image', 'ref_func' => 'media','formObj' => isset($formObj) ? $formObj : null])
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Status') !!}
            <div class="select">
                {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' =>
                'form-control custom-select', 'required' => 'required']) !!}
                <div class="select__arrow"></div>
            </div>
        </div>
    </div>
</div>


<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>
