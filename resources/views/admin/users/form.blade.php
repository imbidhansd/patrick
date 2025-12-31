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
            {!! Form::email('email', null, ['class' => 'form-control max', 'placeholder' => 'Enter Email', 'required' =>
            true, 'maxlength' => 255]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Profile Image') !!}<br />
            {!! Form::file('media', ['class' => 'filestyle']) !!}
            @if ($new_form == false && $formObj->media_id > 0)
            <div class="media_box">
                <a href="{{ asset('/') }}uploads/media/{{ $formObj->media->file_name }}" data-fancybox="gallery">
                    <img src="{{ asset('/') }}uploads/media/fit_thumbs/50x50/{{ $formObj->media->file_name }}"
                        class='img-thumbnail' />
                </a>
                <br />
                <a class="btn img-del-btn btn-danger btn-xs" data-id="{{ $formObj->media_id }}"> Remove</a>
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
            'required' => true, 'maxlength' => 255]) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('User Role') !!}
            {!! Form::select('role_id', $user_roles, null, ['class' => 'form-control custom-select', 'placeholder' => 'Select User Role', 'required' => true]) !!}
        </div>
    </div>
</div>
<hr />
<h4>Other Information</h4>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Designation') !!}
            {!! Form::text('designation', null, ['class' => 'form-control max', 'placeholder' => 'Enter Designation', 'required' => true, 'maxlength' => 255]) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Facebook Page') !!}
            {!! Form::text('facebook_page', null, ['class' => 'form-control max', 'placeholder' => 'Enter Facebook Page', 'required' => false, 'maxlength' => 255]) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Twitter Page') !!}
            {!! Form::text('twitter_page', null, ['class' => 'form-control max', 'placeholder' => 'Enter Twitter Page',
            'required' => false, 'maxlength' => 255]) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Linkedin Page') !!}
            {!! Form::text('linkedin_page', null, ['class' => 'form-control max', 'placeholder' => 'Enter Linkedin Page', 'required' => false, 'maxlength' => 255]) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Phone') !!}
            {!! Form::text('phone', null, ['class' => 'form-control max', 'placeholder' => 'Enter Phone', 'required' => false, 'maxlength' => 255]) !!}
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('About User') !!}
            {!! Form::textarea('about_user', null, ['class' => 'form-control', 'placeholder' => '', 'required' =>
            false]) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Status') !!}
            <div class="select">
                {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' =>
                'form-control custom-select', 'required' => 'required']) !!}
                <div class="select__arrow"></div>
            </div>
        </div>
    </div>
</div>

<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>
