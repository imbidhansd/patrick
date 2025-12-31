@extends('admin.login.layout')
@section('title', 'Invitation to join ' . env('APP_NAME'))

@section('content')


<div class="text-center account-logo-box">
    <div class="mt-2 mb-2">
        <a href="{{ url('admin') }}" class="text-success">
            <span><img src="{{ asset('images/logo.png') }}" alt="{{ env('APP_NAME') }}" height=""></span>
        </a>
    </div>
</div>

<div class="card-body">

    {!! Form::open(['class' => 'form-horizontal module_form', 'autocomplete' => 'off', 'id' => 'invitation_form']) !!}
    {!! Form::hidden('invitation_key') !!}

    <div class="form-group">
        <label>First Name</label>
        {!! Form::text('first_name', $first_name, ['class' => 'form-control', 'required' => true]) !!}
    </div>
    <div class="form-group">
        <label>Last Name</label>
        {!! Form::text('last_name', $last_name, ['class' => 'form-control', 'required' => true]) !!}
    </div>
    <div class="form-group">
        <label>Email</label>
        {!! Form::email('email', $email, ['class' => 'form-control', 'required' => true]) !!}
    </div>
    <div class="form-group">
        <label>Username</label>
        {!! Form::text('username', null, ['class' => 'form-control', 'required' => true, 'data-parsley-type' =>
        'alphanum']) !!}
    </div>
    <div class="form-group">
        <label>Password</label>
        <div class="input-group">
            {!! Form::password('password', ['class' => 'form-control', 'required' => true, 'data-parsley-uppercase' =>
            1,
            'data-parsley-lowercase' => 1, 'data-parsley-number' => 1, 'data-parsley-special' => 1,
            'data-parsley-minlength' => 6, 'data-parsley-maxlength' => 50, 'id' => 'password']) !!}
            <span class="input-group-append view-password">
                <button type="button" class="btn btn-info"><i class="fas fa-eye"></i>
                </button>
            </span>
        </div>
    </div>
    <div class="form-group">
        <label>Confirm Password</label>
        <div class="input-group">
            {!! Form::password('confirm_password', ['class' => 'form-control last_input', 'placeholder' => '',
            'required' =>
            true, 'maxlength' => 255, 'data-parsley-equalto' =>
            '#password']) !!}

            <span class="input-group-append view-password">
                <button type="button" class="btn btn-info"><i class="fas fa-eye"></i>
                </button>
            </span>
        </div>
    </div>

    <div class="form-group account-btn text-center mt-2">
        <div class="col-12">
            <button class="btn width-md btn-bordered btn-danger waves-effect waves-light" type="submit">Submit</button>
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


        $('#invitation_form').submit(function(){

            $.ajax({
                type: 'POST',
                url: $('#invitation_form').attr('action'),
                data: $('#invitation_form').serialize(),
                success: function(data){

                    if (data.status == 0){
                        Swal.fire(
                                'Warning',
                                data.message,
                                'warning'
                            );
                    }else{
                        window.location.href = '{{ url("login") }}';
                    }
                },
                error: function(e){
                    Swal.fire(
                                'Warning',
                                'Error while processing',
                                'warning'
                            );
                },
            });

            return false;
        });



        $('.view-password').mousedown(function(){
            $(this).closest('.input-group').find('input').attr('type','text');
        });
        $('.view-password').mouseup(function(){
            $(this).closest('.input-group').find('input').attr('type','password');
        });
    });
</script>


@endsection
