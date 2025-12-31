<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter Title', 'required' =>
            true]) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Sub Title') !!}
            {!! Form::text('sub_title', null, ['class' => 'form-control', 'placeholder' => 'Enter Sub Title', 'required'
            => false]) !!}
        </div>
    </div>
    <?php /*<div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Date') !!}
            {!! Form::text('date', null, ['class' => 'form-control date_field', 'placeholder' => '', 'required' => true]) !!}
        </div>
    </div>*/ ?>
</div>
<div class="clearfix"></div>
<hr />
<h4>Image</h4>
<div class="row">

    <div class="col-md-4">
        <div class="form-group">
            @include ('admin.includes._file_field', ['field_name' => 'media', 'label_name' => 'Image', 'scope_function'
            => 'media', 'formObj' => isset($formObj) ? $formObj : null])
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('Banner Image') !!}<br />
            {!! Form::file('banner', null, ['class' => 'form-control']) !!}
            @if ($new_form == false && $formObj->banner_id > 0)
            <div class="media_box">
                <a href="{{ asset('/') }}uploads/media/{{ $formObj->banner->file_name }}" data-fancybox="gallery">
                    <img src="{{ asset('/') }}uploads/media/fit_thumbs/50x50/{{ $formObj->banner->file_name }}"
                        class='img-thumbnail' />
                </a>
                <br />
                <a class="btn img-del-btn btn-danger btn-xs" data-id="{{ $formObj->banner_id }}"> Remove</a>
            </div>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('Banner Text') !!}
            {!! Form::text('banner_text', null, ['class' => 'form-control']) !!}
        </div>
    </div>

</div>

@include ('admin.includes._meta_fields')

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Short Content') !!}
            {!! Form::textarea('short_content', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Content') !!}
            {!! Form::textarea('content', null, ['class' => 'form-control ckeditor']) !!}
        </div>
    </div>
    <div class="clearfix"></div>
    <hr />

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Show On Homepage') !!}
            <div class="select">
                {!! Form::select('show_on_homepage', ['no' => 'No', 'yes' => 'Yes'], null, ['class' => 'form-control
                custom-select',
                'required' => 'required']) !!}
                <div class="select__arrow"></div>
            </div>
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
