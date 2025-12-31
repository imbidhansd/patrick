@extends('company.register.blank-layout')
@section('content')

<div class="col-md-4 offset-md-4 text-center">
    <a href="{{ url('/') }}">
        <img src="https://s3.us-west-2.amazonaws.com/images.trustpatrick.com/trust_patrick_rn_logo_v1.png" />
    </a>
    <div class="clearfix">&nbsp;</div>
    <i class="mdi mdi-checkbox-marked-circle-outline text-success font-50"></i>
    <div class="clearfix">&nbsp;</div>

    <p class="big_fonts">
        Thank you for registering!
        <br />
        Please login and complete your account setup!
    </p>
    <div class="clearfix">&nbsp;</div>
    <a href="{{ url('login') }}" class="btn btn-info">Go To Login <i class="fas fa-sign-in-alt"></i></a>
</div>
@endsection
