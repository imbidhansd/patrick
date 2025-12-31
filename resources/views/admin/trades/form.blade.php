<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control max', 'placeholder' => 'Enter Title', 'required' =>
            true, 'maxlength' => 255]) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Short Name') !!}
            {!! Form::text('short_name', null, ['class' => 'form-control max', 'placeholder' => 'Enter Short Name', 'required' =>
            true, 'maxlength' => 255]) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Image') !!}<br />
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
    <?php /*<div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Status') !!}
            <div class="select">
                {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' =>
                'form-control custom-select', 'required' => 'required']) !!}
                <div class="select__arrow"></div>
            </div>
        </div>
    </div>*/ ?>
</div>


<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>
