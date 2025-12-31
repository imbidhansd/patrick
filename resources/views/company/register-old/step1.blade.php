<div class="row">

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('First Name') !!}
            {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => '', 'required' => true,
            'maxlength' => 255, 'data-parsley-group' => 'step_1']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Last Name') !!}
            {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => '',
            'required' => true, 'maxlength' => 255, 'data-parsley-group' => 'step_1']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Email Address') !!}
            {!! Form::email('email', null, ['id' => 'email', 'class' => 'form-control', 'placeholder' =>
            '', 'required' => true, 'maxlength' => 255, 'data-parsley-group' => 'step_1']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Enter Username') !!}
            {!! Form::text('username', null, ['id' => 'username','class' => 'form-control', 'placeholder' =>
            '', 'required' => true, 'maxlength' => 255, 'data-parsley-group' => 'step_1',
            'data-parsley-type' => 'alphanum']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Enter Password') !!}
            {!! Form::password('password', ['id' => 'password','class' => 'form-control', 'placeholder'
            =>
            '',
            'required' =>
            true, 'maxlength' => 255, 'data-parsley-group' => 'step_1', 'data-parsley-uppercase' => 1,
            'data-parsley-lowercase' => 1, 'data-parsley-number' => 1, 'data-parsley-special' => 1,
            'data-parsley-minlength' => 6, 'data-parsley-maxlength' => 50]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Enter Confirm Password') !!}
            {!! Form::password('confirm_password', ['class' => 'form-control last_input', 'placeholder' => '',
            'required' =>
            true, 'maxlength' => 255, 'data-parsley-group' => 'step_1', 'data-parsley-equalto' =>
            '#password']) !!}
        </div>
    </div>

</div>

<div class="clearfix">&nbsp;</div>
<button type="button" class="btn btn-info float-md-right next-btn current_step_submit_btn" id="submit-step-1">Save &
    Proceed</button>
<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>
