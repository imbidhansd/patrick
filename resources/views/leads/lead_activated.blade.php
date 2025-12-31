@extends('admin.blank-layout')
@section('content')

<div class="col-4 offset-md-4 offset-sm-4 text-center">
    <a href="{{ url('/') }}">
        <img src="https://s3.us-west-2.amazonaws.com/images.trustpatrick.com/trust_patrick_rn_logo_v1.png"  alt="">
    </a>
    <div class="clearfix">&nbsp;</div>
    <i class="mdi mdi-checkbox-marked-circle-outline text-success font-70"></i>
    
    <h3>Your confirmation has been received.</h3>
    <p class="big_fonts">
        Please check your email inbox.
        <br />
        Thank you!
    </p>
    <div class="clearfix">&nbsp;</div>
    <!--a href="{{ url('login') }}" class="btn btn-info">Go To Login <i class="fas fa-sign-in-alt"></i></a-->
</div>
@endsection
