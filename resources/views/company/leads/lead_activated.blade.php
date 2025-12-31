@extends('admin.blank-layout')
@section('content')

<div class="col-4 offset-md-4 offset-sm-4 text-center">
    <a href="{{ url('/') }}">
        <img src="{{ asset('/images/header-logo.png') }}" />
    </a>
    <div class="clearfix">&nbsp;</div>
    <i class="mdi mdi-checkbox-marked-circle-outline text-success font-70"></i>
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
