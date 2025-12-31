@extends('admin.login.layout')
@section('title', 'Company Login - ' . env('APP_NAME'))

@section('content')

<div class="text-center account-logo-box">
    <div class="mt-2 mb-2">
        <a href="{{ url('admin') }}" class="text-success">
            <span><img src="https://s3.us-west-2.amazonaws.com/images.trustpatrick.com/trust_patrick_rn_logo_v1.png" alt="{{ env('APP_NAME') }}" /></span>
        </a>
    </div>
</div>

<div class="card-body">

    {!! Form::open(['url' => url('reset-password'), 'method' => 'POST', 'class' => 'login_form form-horizontal module_form', 'autocomplete' => 'off']) !!}

    {!! Form::hidden('forgot_password_key', $company_user->forgot_password_key) !!}
    <div class="form-group">
        <div class="input-group">
            {!! Form::password('password', ['class' => 'form-control', 'required' => true, 'placeholder' => 'Password', 'id' => 'password', 'maxlength' => 255, 'data-parsley-minlength' => 6, 'autocomplete' => 'off']) !!}
            <span class="input-group-append view-password">
                <button type="button" class="btn btn-info"><i class="fas fa-eye"></i>
                </button>
            </span>
        </div>
    </div>

    <div class="form-group">
        <div class="input-group">
            {!! Form::password('confirm_password', ['class' => 'form-control last_input', 'required' => true, 'placeholder' => 'Confirm Password', 'maxlength' => 255, 'data-parsley-equalto' => '#password']) !!}
            <span class="input-group-append view-password">
                <button type="button" class="btn btn-info"><i class="fas fa-eye"></i>
                </button>
            </span>
        </div>
    </div>

    <div class="form-group account-btn text-center">
        <div class="col-12">
            <button class="btn width-md btn-bordered btn-danger waves-effect waves-light" type="submit">Reset Password</button>
        </div>
    </div>
    {!! Form::hidden('recaptcha', null, ['id' => 'recaptcha']) !!}
    {!! Form::close() !!}

</div>
<!-- end card-body -->

@endsection


@section('page_js')
@include ('admin.includes.captcha_js', ['action_field' => 'login'])
<script type="text/javascript">
    $(function(){
        $('.view-password').mousedown(function(){
            $(this).closest('.input-group').find('input').attr('type','text');
        });
        
        $('.view-password').mouseup(function(){
            $(this).closest('.input-group').find('input').attr('type','password');
        });
        
        if(navigator.userAgent.match(/Android|BlackBerry|iPhone|iPad|iPod|Opera Mini|IEMobile/i)){
            $('.view-password').on("touchstart", function (){
                $(this).closest('.input-group').find('input').attr('type','text');
            });
            $('.view-password').on("touchend", function (){
                $(this).closest('.input-group').find('input').attr('type','password');
            });
        }
    });
</script>
@endsection
