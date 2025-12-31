<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Name') !!}
            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter name', 'required' =>
            'required']) !!}
            <i>Note: No spcial characters. use only underscore and alphanumeric</i>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter Setting Title', 'required'
            => 'required']) !!}
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Help Text') !!}
            {!! Form::text('help_text', null, ['class' => 'form-control', 'placeholder' => 'Enter Help Text']) !!}
        </div>
    </div>
    <div class="clearfix"></div>
    <hr />
    <?php
    $field_type = [
        'text' => 'text',
        'textarea' => 'textarea',
        'radio' => 'radio',
        'select' => 'select',
        'image' => 'image',
    ];
    ?>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Field Type') !!}
            {!! Form::select('field_type', $field_type, null, ['class' => 'form-control custom-select', 'placeholder' =>
            'Select Field
            Type']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Field Options') !!}
            {!! Form::text('field_options', null, ['class' => 'form-control', 'placeholder' => 'Enter Field Options'])
            !!}
            <i>Enter each option with comma separated (like Option1, Option2, Option3)</i>
        </div>
    </div>
    <div class="clearfix"></div>
    <hr />
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Min Value') !!}
            {!! Form::text('min_value', null, ['class' => 'form-control', 'placeholder' => 'Enter Min Value']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Max Value') !!}
            {!! Form::text('max_value', null, ['class' => 'form-control', 'placeholder' => 'Enter Max Value']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Min Length') !!}
            {!! Form::text('min_length', null, ['class' => 'form-control', 'placeholder' => 'Enter Min Length']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Max Length') !!}
            {!! Form::text('max_length', null, ['class' => 'form-control', 'placeholder' => 'Enter Max Length']) !!}
        </div>
    </div>


</div>


<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>
