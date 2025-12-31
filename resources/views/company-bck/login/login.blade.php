@extends('admin.login.layout')
@section('title', 'Admin Login - ' . env('APP_NAME'))

@section('content')

<div class="text-center account-logo-box">
    <div class="mt-2 mb-2">
        <a href="{{ url('admin') }}" class="text-success">
            <span><img src="{{ asset('images/logo.png') }}" alt="{{ env('APP_NAME') }}" height=""></span>
        </a>
    </div>
</div>

<div class="card-body">

    {!! Form::open(['class' => 'login_form form-horizontal module_form', 'autocomplete' => 'off']) !!}

    <div class="form-group">
        {!! Form::text('email', null, ['class' => 'form-control', 'required' => 'required',
        'placeholder' => 'Email Or Username', 'autocomplete' => 'off' , 'autofocus' =>
        true]) !!}
    </div>

    <div class="form-group">
        {!! Form::password('password', ['class' => 'form-control', 'required' => 'required', 'placeholder' =>
        'Password', 'data-parsley-minlength' => 6, 'autocomplete' => 'off']) !!}
    </div>

    <div class="form-group account-btn text-center mt-2">
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
