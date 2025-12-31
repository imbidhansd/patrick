@extends('admin.blank-layout')
@section('title', $admin_page_title)

@section ('content')


<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box text-center">
            <img src="{{ asset('/images/header-logo.png') }}" />
            <!--h1>LOGO HERE</h1-->
            <h4 class="page-title ">{{ $admin_page_title }}</h4>
        </div>
    </div>
</div>
<!-- end page title end breadcrumb -->

<!-- row -->

<div class="row">
    <div class="col-md-4 offset-md-4">
        @include('flash::message')
        <div class="card-box text-center">

            <img src="{{ $qr_code }}" />
            <p><i>Either Scan QR code or add following code in Google Authentication App.</i></p>
            <h3 class="text-dark">{{ $secret }}</h3>
            <div class="clearfix">&nbsp;</div>
            {!! Form::open(['class' => 'module_form']) !!}

            <div class="form-group">
                <div class="checkbox checkbox-primary">
                    <input id="checkbox1" type="checkbox" required="true">
                    <label for="checkbox1">
                        I scanned QR Code or Added Secret Code to <span class="text-danger">Google Auth App</span>.
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-info">Proceed To Login <i
                    class="fas fa-angle-right"></i></button>
            {!! Form::close() !!}
            <div class="clearfix">&nbsp;</div>

        </div>
    </div>
</div> <!-- end row -->

<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>


@stop
