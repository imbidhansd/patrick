<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('First Name') !!}
            {!! Form::text('first_name', null, ['class' => 'form-control max', 'placeholder' => 'Enter First Name', 'required' => true, 'maxlength' => 255]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Last Name') !!}
            {!! Form::text('last_name', null, ['class' => 'form-control max', 'placeholder' => 'Enter Last Name',
            'required' => true, 'maxlength' => 255]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Email') !!}
            {!! Form::email('email', null, ['class' => 'form-control max', 'placeholder' => 'Enter Email', 'required' => true, 'maxlength' => 255]) !!}
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
            {!! Form::label('Company User Type') !!}
            {!! Form::select('company_user_type', ['company_super_admin' => 'Company Super Admin', 'company_admin' => 'Company Admin'], null, ['class' => 'form-control', 'placeholder' => 'Select Company User Type', 'required' => true]) !!}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Username') !!}
            {!! Form::text('username', null, ['class' => 'form-control max', 'placeholder' => 'Enter Username',
            'required' => true, 'maxlength' => 255]) !!}
        </div>
    </div>
</div>
<hr />
<h4>Other Information</h4>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Phone') !!}
            {!! Form::text('user_telephone', null, ['class' => 'form-control', 'placeholder' => 'Enter Phone', 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000', 'required' => false]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Address') !!}
            {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'Enter Address', 'required' => false]) !!}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('City') !!}
            {!! Form::text('city', null, ['class' => 'form-control', 'placeholder' => 'Enter City', 'required' => false]) !!}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('State') !!}
            {!! Form::select('state_id', $states, null, ['class' => 'form-control custom-select', 'placeholder' => 'Select State', 'required' => false]) !!}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('Zipcode') !!}
            {!! Form::text('zipcode', null, ['class' => 'form-control', 'placeholder' => 'Enter zipcode', 'data-toggle' => 'input-mask', 'data-mask-format' => '00000', 'required' => false]) !!}
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('User Bio') !!}
            {!! Form::textarea('user_bio', null, ['class' => 'form-control ckeditor', 'placeholder' => '', 'required' =>
            false]) !!}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Status') !!}
            {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' =>
                'form-control custom-select', 'required' => true]) !!}
        </div>
    </div>
</div>

<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>


@push('page_scripts')
<script src="{{ asset('/') }}thirdparty/ckeditor/ckeditor.js"></script>

<!-- Plugins js -->
<script src="{{ asset('/themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/js/pages/form-masks.init.js') }}"></script>

<script>
    CKEDITOR.replace('user_bio', {
        filebrowserImageUploadUrl: '{{ url("admin/media/editorupload") }}',
        filebrowserUploadMethod: 'form'
    });
</script>
@endpush