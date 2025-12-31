@extends('admin.blank-layout')
@section('content')

<div class="col-4 offset-md-4 offset-sm-4 text-center">

    <a href="{{ url('/') }}">
        <img src="{{ asset('images/logo.png') }}" />
    </a>
    <div class="clearfix">&nbsp;</div>

    <h2 class="text-success">Thank you for your registering your account!</h2>

    <h3 class="text-warning">You are Almost Finished</h3>
    <h5 class="blue_text">Please confirm your account registration</h5>

    <div class="clearfix">&nbsp;</div>
    <h6 class="text-danger">**Important**</h6>
    <div class="clearfix">&nbsp;</div>

    <p class="big_fonts">
        If you do not receive an email confirmation with a few minutes, please check your inbox/spam folder.
    </p>
    <div class="clearfix">&nbsp;</div>
    <p>
        If you still do not receive the confirmation email within next 10 minutes, please email us at <a
            href="mailto: listing@trustpatrick.com">listing@trustpatrick.com</a>
    </p>
    <div class="clearfix">&nbsp;</div>

    <a href="#" class="btn btn-info">Go To Login</a>
</div>

@endsection
