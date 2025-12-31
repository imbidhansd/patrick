<?php
    $admin_page_title = 'Change Password';
?>
@extends('company.layout')


@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <div class="row">
                <div class="col-xl-6 col-md-6 offset-xl-3 offset-md-3">
                    <h4 class="mb-5 text-center text-info">Change Password Of Your Profile</h4>

                    {!! Form::open(['url' => url('change-password'), 'class' => 'module_form full_width']) !!}
                    <div class="form-group">
                        <label>Old Password</label>
                        <div class="input-group">
                            {!! Form::password('old_password', ['class' => 'form-control', 'required' => 'required',
                            'id' => 'password', 'data-parsley-minlength' => 6, 'data-parsley-maxlength' => 50]) !!}
                            <span class="input-group-append view-password">
                                <button type="button" class="btn btn-info"><i class="fas fa-eye"></i></button>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>New Password</label>
                        <div class="input-group">
                            {!! Form::password('new_password', ['class' => 'form-control', 'required' => 'required',
                            'id' =>
                            'new_password', 'data-parsley-minlength' => 6, 'data-parsley-maxlength' => 50]) !!}
                            <span class="input-group-append view-password">
                                <button type="button" class="btn btn-info"><i class="fas fa-eye"></i></button>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Confirm Password</label>
                        {!! Form::password('confirm_password', ['class' => 'form-control', 'required' =>
                        'required', 'data-parsley-equalto' => '#new_password']) !!}
                    </div>

                    <div class="text-left">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save changes</button>
                    </div>
                    {!! Form::close() !!}

                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section ('page_js')
<script type="text/javascript">
    $(document).ready(function (){
        $('.view-password').mousedown(function(){
            $(this).closest('.input-group').find('input').attr('type','text');
        });

        $('.view-password').mouseup(function(){
            $(this).closest('.input-group').find('input').attr('type','password');
        });
    });
</script>

@endsection
