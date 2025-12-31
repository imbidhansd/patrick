@extends('admin.login.layout')
@section('title', 'Company Login - ' . env('APP_NAME'))

@section('content')
<div class="text-center account-logo-box">
    <div class="mt-2 mb-2">
        <a href="{{ url('admin') }}" class="text-success">
            <span><img src="https://s3.us-west-2.amazonaws.com/images.trustpatrick.com/trust_patrick_rn_logo_v1.png"  alt="{{ env('APP_NAME') }}" /></span>
        </a>
    </div>
</div>
<div class="card-body">
    {!! Form::open(['class' => 'login_form form-horizontal module_form', 'autocomplete' => 'off']) !!}
    <div class="form-group">
        {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' =>
        'Username Or Email', 'autocomplete' => 'off', 'required' => true]) !!}
    </div>

    <div class="form-group text-center">
        <a href="{{ url('login') }}">Back To Login</a>
    </div>
    <div class="form-group account-btn text-center mt-2">
        <div class="col-12">
            <button class="btn width-md btn-bordered btn-danger waves-effect waves-light" type="submit">Submit</button>
        </div>
    </div>
    {!! Form::close() !!}
</div>
<!-- end card-body -->
@endsection


@section('page_js')
@endsection
