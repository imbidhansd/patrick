@extends('company.register.blank-layout')
@section('content')

<div class="col-md-4 offset-md-4 text-center">
    <a href="{{ url('/') }}">
        <img src="https://s3.us-west-2.amazonaws.com/images.trustpatrick.com/trust_patrick_rn_logo_v1.png" " />
    </a>
    <div class="clearfix">&nbsp;</div>
    <i class="mdi mdi-checkbox-marked-circle-outline text-success font-70"></i>
    <div class="clearfix">&nbsp;</div>

    <p class="big_fonts">
        Thank you!
        <br />
        @include('flash::message')
    </p>
</div>
@endsection
