@extends('company.register.blank-layout')
@section('content')

<div class="col-md-8 offset-md-2 text-center">
    <a href="{{ url('/') }}">
        <img src="https://s3.us-west-2.amazonaws.com/images.trustpatrick.com/trust_patrick_rn_logo_v1.png" />
    </a>
    <div class="clearfix">&nbsp;</div>

    <h3 class="registration_thankyou_color">Thank you for your registering your account!</h3>
    
    <div class="clearfix">&nbsp;</div>
    
    <h4 class="text-info">You're Almost Finished!</h4>
    <h5 class="blue_text">Please confirm your account registration</h5>

    <div class="clearfix">&nbsp;</div>
    <h6 class="text-danger">*Important*</h6>

    <h4 class="text-info">Please Check Your Email</h4>
    <p class="big_fonts mt-4 mb-1">
        If you do not receive an email confirmation with a few minutes, please check your inbox/spam folder.
    </p>
    <p>
        If you still do not receive the confirmation email within next 10 minutes, please 
        <a href="https://opp.trustpatrick.com/#contact" target="_blank">contact member support</a>.
    </p>
    <div class="clearfix">&nbsp;</div>
    <div class="clearfix">&nbsp;</div>
    <div class="clearfix">&nbsp;</div>
    <div class="clearfix">&nbsp;</div>
</div>

@endsection
