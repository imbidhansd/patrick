<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Membership Level') !!}
            {!! Form::select('membership_level_id', $membership_levels, null, ['class' => 'form-control custom-select', 'id' => 'membership_level_id', 'placeholder' => 'Select Membership Level', 'required' => false]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Membership Status') !!}            
            {!! Form::select('membership_status_id', [], null, ['class' => 'form-control custom-select', 'id' => 'membership_status_id', 'placeholder' => '', 'required' => false]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Question') !!}
            {!! Form::text('title', null, ['class' => 'form-control max', 'placeholder' => 'Enter Question', 'required' =>
            true, 'maxlength' => 255]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Vimeo Video ID') !!}
            {!! Form::text('video_id', null, ['class' => 'form-control max', 'placeholder' => 'Enter Vimeo Video ID', 'maxlength' => 255]) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Content') !!}
            {!! Form::textarea('content', null, ['class' => 'form-control ckeditor']) !!}
        </div>
    </div>
</div>
<hr />

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Status') !!}
            <div class="select">
                {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' =>
                'form-control custom-select', 'required' => true]) !!}
                <div class="select__arrow"></div>
            </div>
        </div>
    </div>
</div>


<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>


@push('form_js')
<script src="{{ asset('/') }}thirdparty/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    CKEDITOR.replace('content', {
        filebrowserImageUploadUrl: '{{ url("admin/media/editorupload") }}',
        filebrowserUploadMethod: 'form'
    });
</script>
@include('admin.faqs._js')
@endpush