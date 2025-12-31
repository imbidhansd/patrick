<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('PreSceen Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter PreSceen Title', 'required' => true]) !!}
        </div>
    </div>
</div>

<hr />
<div class="row">
    <div class="col-md-6">
        @include ('admin.includes._img_field', ['label' => 'Image', 'ref_func' => 'media','formObj' => isset($formObj) ? $formObj : null])
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Price') !!}
            {!! Form::text('price', null, ['class' => 'form-control', 'placeholder' => 'Enter Price', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Quantity') !!}
            {!! Form::text('quantity', null, ['class' => 'form-control', 'placeholder' => 'Enter Maximum Quantity', 'required' => true]) !!}
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

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Status') !!}
            <div class="select">
                {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' =>
                'form-control', 'required' => 'required']) !!}
                <div class="select__arrow"></div>
            </div>
        </div>
    </div>
</div>


<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>

@push('page_scripts')
<script src="{{ asset('/') }}thirdparty/ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content', {
        filebrowserImageUploadUrl: '{{ url("admin/media/editorupload") }}',
        filebrowserUploadMethod: 'form'
    });
</script>
@endpush