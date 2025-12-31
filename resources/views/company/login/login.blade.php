@extends('admin.login.layout')
@section('title', 'Company Login - ' . env('APP_NAME'))

@section('content')

<div class="text-center account-logo-box">
    <div class="mt-0 mb-0">
        <a href="{{ url('admin') }}" class="text-success">
            <span><img src="https://s3.us-west-2.amazonaws.com/images.trustpatrick.com/trust_patrick_rn_logo_v1.png"  alt="{{ env('APP_NAME') }}" /></span>
        </a>
    </div>
</div>

<div class="card-body">

    {!! Form::open(['url' => url('login'), 'method' => 'POST', 'class' => 'login_form form-horizontal module_form', 'autocomplete' => 'off']) !!}

    <div class="form-group">
        {!! Form::text('username', null, ['class' => 'form-control', 'required' => 'required',
        'placeholder' => 'Username Or Email', 'autocomplete' => 'off' , 'autofocus' =>
        true]) !!}
    </div>

    <div class="form-group">
        {!! Form::password('password', ['class' => 'form-control', 'required' => 'required', 'placeholder' =>
        'Password', 'data-parsley-minlength' => 6, 'autocomplete' => 'off']) !!}
    </div>

    <div class="form-group text-center">
        <a href="{{ url('forgot-password') }}" class="text-muted">Forgot Password?</a> <br />
        <!--a href="{{ url('forgot-username') }}"  class="text-muted">Forgot Username?</a-->
    </div>
    
    <div class="row">
        <div class="col-12 text-center">
            <div class="g-recaptcha" id="login-recaptcha" data-callback="imNotARobot" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY')  }}"></div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 text-center">
            <button class="btn width-md btn-bordered btn-danger waves-effect waves-light login_btn" type="submit" disabled>Log In</button>
        </div>
    </div>
    

    <?php /* <div class="form-group account-btn text-center">
        <div class="col-12">
            <button class="btn width-md btn-bordered btn-danger waves-effect waves-light login_btn" type="submit" disabled>Log In</button>
        </div>
    </div> */ ?>
    
    
    <?php /* {!! Form::hidden('recaptcha', null, ['id' => 'recaptcha']) !!} */ ?>
    {!! Form::close() !!}

</div>
<!-- end card-body -->

@endsection


@section('page_js')

<style type="text/css">
    .g-recaptcha div:first-child{
        margin: 0 auto 10px auto !important;
        float: none;
    }
</style>


<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php /* @include ('admin.includes.captcha_js', ['action_field' => 'login']) */ ?>

<script type="text/javascript">
    var imNotARobot = function() {
        //console.info("Button was clicked");
        $(".login_btn").attr("disabled", false);
    };
    
    $(function(){
        $('.resend-activation-link').click(function(){

            var url = $(this).attr('href');
            $.ajax({
                type: 'POST',
                url: url, 
                data: { '_token': '{{ csrf_token() }}'},
                success: function(){
                    window.location.reload();
                },
                error: function(){
                    alert ('error');
                },
            });

            return false;

        });
    }) ;   
</script>

@endsection
