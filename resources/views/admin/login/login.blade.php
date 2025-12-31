@extends('admin.login.layout')
@section('title', 'Admin Login - ' . env('APP_NAME'))

@section('content')


<div class="text-center account-logo-box">
    <div class="mt-0 mb-0">
        <a href="{{ url('admin') }}" class="text-success">
            <span><img src="https://s3.us-west-2.amazonaws.com/images.trustpatrick.com/trust_patrick_rn_logo_v1.png"  alt="{{ env('APP_NAME') }}" height=""></span>
        </a>
    </div>
</div>

<div class="card-body">
    {!! Form::open(['class' => 'login_form form-horizontal module_form', 'autocomplete' => 'off']) !!}
    <h5 class="text-center mt-0 p-3">Administrator Login</h5>
    
    <div class="form-group">
        {!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required',
        'data-parsley-type'=> 'alphanum' , 'placeholder' => 'Email', 'autocomplete' => 'off' , 'autofocus' =>
        true]) !!}
    </div>

    <div class="form-group">
        {!! Form::password('password', ['class' => 'form-control', 'required' => 'required', 'placeholder' =>
        'Password', 'data-parsley-minlength' => 6, 'autocomplete' => 'off']) !!}
    </div>

    <div class="clearfix">&nbsp;</div>

    <div class="form-group account-btn text-center">
        <div class="col-12">
            <button class="btn width-md btn-bordered btn-danger waves-effect waves-light" type="submit">Log In</button>
        </div>
    </div>
    {!! Form::hidden('recaptcha', null, ['id' => 'recaptcha']) !!}
    {!! Form::close() !!}

</div>
<!-- end card-body -->


@endsection

@section('page_js')
@include ('admin.includes.captcha_js', ['action_field' => 'login'])
@endsection
