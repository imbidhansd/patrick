@extends('admin.layout')
@section('title', $admin_page_title)

@section ('content')

<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title text-center">{{ $admin_page_title }}</h4>
        </div>
    </div>
</div>
<!-- end page title end breadcrumb -->


<!-- row -->

@include('flash::message')
<div class="card-box">
    {!! Form::model($user, ['url' => url('admin/update-profile'), 'class' => 'module_form', 'files' => true, ])
    !!}

    @include('admin.includes.formErrors')



    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('First Name') !!}
                {!! Form::text('first_name', null, ['class' => 'form-control max', 'placeholder' => 'Enter First Name',
                'required' =>
                true, 'maxlength' => 255]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Last Name') !!}
                {!! Form::text('last_name', null, ['class' => 'form-control max', 'placeholder' => 'Enter Last Name',
                'required'
                => true, 'maxlength' => 255]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Email') !!}
                {!! Form::email('email', null, ['class' => 'form-control max', 'placeholder' => 'Enter Email',
                'required' =>
                true, 'maxlength' => 255]) !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Profile Image') !!}<br />
                {!! Form::file('media', ['class' => 'filestyle']) !!}
                @if ($user->media_id > 0)
                <div class="media_box">
                    <a href="{{ asset('/') }}uploads/media/{{ $user->media->file_name }}" data-fancybox="gallery">
                        <img src="{{ asset('/') }}uploads/media/fit_thumbs/50x50/{{ $user->media->file_name }}"
                            class='img-thumbnail' />
                    </a>
                    <br />
                    <a class="btn img-del-btn btn-danger btn-xs" data-id="{{ $user->media_id }}"> Remove</a>
                </div>
                @endif
            </div>
        </div>
    </div>
    <hr />
    <h4>Login Information</h4>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Username') !!}
                {!! Form::text('username', null, ['class' => 'form-control max', 'placeholder' => 'Enter Username',
                'required'
                =>
                true, 'maxlength' => 255]) !!}
            </div>
        </div>

    </div>
    <hr />
    <h4>Other Information</h4>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Designation') !!}
                {!! Form::text('designation', null, ['class' => 'form-control max', 'placeholder' => 'Enter
                Designation',
                'required' =>
                true, 'maxlength' => 255]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Facebook Page') !!}
                {!! Form::text('facebook_page', null, ['class' => 'form-control max', 'placeholder' => 'Enter Facebook
                Page',
                'required' => false, 'maxlength' => 255]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Twitter Page') !!}
                {!! Form::text('twitter_page', null, ['class' => 'form-control max', 'placeholder' => 'Enter Twitter
                Page',
                'required' => false, 'maxlength' => 255]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Linkedin Page') !!}
                {!! Form::text('linkedin_page', null, ['class' => 'form-control max', 'placeholder' => 'Enter Linkedin
                Page',
                'required' => false, 'maxlength' => 255]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Phone') !!}
                {!! Form::text('phone', null, ['class' => 'form-control max', 'placeholder' => 'Enter Phone',
                'required' => false, 'maxlength' => 255]) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('About User') !!}
                {!! Form::textarea('about_user', null, ['class' => 'form-control', 'placeholder' => '', 'required' =>
                false]) !!}
            </div>
        </div>
    </div>


    <hr />
    <button type="submit" class="btn btn-info float-right waves-effect waves-light">Update Profile</button>
    <div class="clearfix"></div>
</div>

{!! Form::close() !!}

</div> <!-- end row -->

@stop
