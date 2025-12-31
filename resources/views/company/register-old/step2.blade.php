<div class="row">

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Company Name') !!}
            {!! Form::text('company_name', null, ['class' => 'form-control', 'placeholder' => '',
            'required'
            =>
            true, 'maxlength' => 255, 'data-parsley-group' => 'step_2']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Company Website') !!}
            {!! Form::text('company_website', null, ['class' => 'form-control', 'placeholder' => '',
            'required' =>
            true, 'maxlength' => 255, 'data-parsley-group' => 'step_2']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Main Company Telephone') !!}
            {!! Form::text('main_company_telephone', null, ['class' => 'form-control',
            'required' => true, 'maxlength' => 255, 'data-parsley-group' => 'step_2', 'data-toggle' =>
            'input-mask',
            'data-mask-format' => '(000) 000-0000']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Secondary Telephone') !!}
            {!! Form::text('secondary_telephone', null, ['class' => 'form-control',
            'required' => false, 'maxlength' => 255, 'data-parsley-group' => 'step_2',
            'data-toggle' => 'input-mask',
            'data-mask-format' => '(000) 000-0000']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Company Mailling Address') !!}
            {!! Form::text('company_mailing_address', null, ['class' => 'form-control',
            'required' => true, 'maxlength' => 255, 'data-parsley-group' => 'step_2']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Suite') !!}
            {!! Form::text('suite', null, ['class' => 'form-control',
            'required' => false, 'maxlength' => 255, 'data-parsley-group' => 'step_2']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('City') !!}
            {!! Form::text('city', null, ['class' => 'form-control',
            'required' => true, 'maxlength' => 255, 'data-parsley-group' => 'step_2']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('State') !!}
            {!! Form::select('state_id', $states, null, ['class' => 'form-control custom-select',
            'required' => true, 'maxlength' => 255, 'data-parsley-group' => 'step_2', 'placeholder' => 'Select']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Zipcode') !!}
            {!! Form::text('zipcode', null, ['class' => 'form-control last_input',
            'required' => true, 'maxlength' => 5, 'data-parsley-group' => 'step_2',
            'data-toggle' => 'input-mask',
            'data-mask-format' => '00000']) !!}
        </div>
    </div>

</div>

<div class="clearfix">&nbsp;</div>
<button type="button" class="btn btn-dark back-btn">Back</button>
<button type="button" class="btn btn-info float-md-right next-btn current_step_submit_btn" id="submit-step-2">Save &
    Proceed</button>
<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>
