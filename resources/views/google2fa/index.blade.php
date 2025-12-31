@extends('admin.login.layout')
@section('title', '2FA Verification - ' . env('SITE_TITLE'))


@section('content')






<div class="text-center account-logo-box">
    <div class="mt-2 mb-2">
        <a href="{{ url('admin') }}" class="text-success">
            <span><img src="{{ asset('/images/header-logo.png') }}" alt="{{ env('APP_NAME') }}" height=""></span>
        </a>
    </div>
</div>

<div class="card-body">

    {!! Form::open(['url' => route('2fa'),'class' => 'login_form form-horizontal module_form', 'autocomplete' =>
    'off']) !!}

    <h4 class='text-center'>Enter your OTP from <br /><span class="text-success">Google Authenticator</span></h4>
    <div class="clearfix">&nbsp;</div>
    <div class="form-group">
        <label for="one_time_password" class="text-center">One Time Password</label>
        {!! Form::number('one_time_password', null , ['class' => 'form-control text-center', 'required' => true, 'autofocus' => true, 'data-parsley-maxlength' => 6, '	data-parsley-type' => 'integer', 'data-parsley-error-message' => 'Enter Valid OTP']) !!}

    </div>

    <div class="form-group account-btn text-center mt-2">
        <div class="col-12">
            <button class="btn width-md btn-bordered btn-danger waves-effect waves-light" type="submit">Submit</button>
        </div>
    </div>
    {!! Form::close() !!}

</div>
<!-- end card-body -->


<!-- end card-box-->

@stop
