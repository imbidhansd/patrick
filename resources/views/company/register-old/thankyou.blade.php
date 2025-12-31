@extends('admin.blank-layout')
@section('content')

<div class="col-6 offset-md-3 offset-sm-3 text-center">

    <a href="{{ url('/') }}">
        <img src="{{ asset('images/logo.png') }}" />
    </a>
    <div class="clearfix">&nbsp;</div>

    <h3 class="text-success">Thank you for your registering your account!</h3>

    <h4 class="text-warning">You are Almost Finished</h4>
    <h5 class="blue_text">Please confirm your account registration</h5>

    <div class="clearfix">&nbsp;</div>
    <h6 class="text-danger">**Important**</h6>

    <p class="big_fonts">
        If you do not receive an email confirmation with a few minutes, please check your inbox/spam folder.
    </p>
    <p>
        If you still do not receive the confirmation email within next 10 minutes, please email us at <a
            href="mailto: listing@trustpatrick.com">listing@trustpatrick.com</a>
    </p>
    <div class="clearfix">&nbsp;</div>

    <a href="{{ url('login') }}" class="btn btn-info">Go To Login</a>
    <div class="clearfix">&nbsp;</div>
    <div class="clearfix">&nbsp;</div>
    <div class="clearfix">&nbsp;</div>
</div>

@endsection
