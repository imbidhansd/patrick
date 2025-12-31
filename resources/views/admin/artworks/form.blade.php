<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter title', 'required' => true]) !!}
        </div>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('Artwork For') !!}
            {!! Form::select('artwork_for', $banner_for_options, null, ['class' => 'form-control custom-select', 'placeholder' => 'Select Artwork For', 'required' => true]) !!}
        </div>
    </div>
    <?php /* <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('Artwork Type') !!}
            {!! Form::select('artwork_type', $artworkTypeArr, null, ['class' => 'form-control custom-select', 'placeholder' => 'Select Artwork Type', 'required' => true]) !!}
        </div>
    </div> */ ?>
    
    @if (isset($artwork_type) && $artwork_type == 'social_media')
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('Social Type') !!}
            {!! Form::select('social_type', $social_type, null, ['class' => 'form-control custom-select', 'placeholder' => 'Select Social Type', 'required' => true]) !!}
        </div>
    </div>
    @endif
    
    <?php /* @if (isset($artwork_type) && $artwork_type == 'print_ready')
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('Type') !!}
            {!! Form::select('image_type', ['pdf' => 'PDF', 'image' => 'Image'], null, ['class' => 'form-control custom-select', 'placeholder' => 'Select Type', 'required' => true]) !!}
        </div>
    </div>
    @endif */ ?>
</div>
<hr />
<div class="row">
    <div class="col-md-4">
        @include ('admin.includes._img_field', ['label' => 'Image', 'ref_func' => 'jpg_media', 'formObj' => isset($formObj) ? $formObj : null, 'accept' => 'image/*'])
    </div>
    
    @if (isset($artwork_type) && $artwork_type == 'print_ready')
    <div class="col-md-4">
        @include ('admin.includes._img_field', ['label' => 'PDF', 'ref_func' => 'pdf_media', 'formObj' => isset($formObj) ? $formObj : null, 'accept' => 'application/pdf'])
    </div>
    @endif
    
    <?php /* <div class="col-md-4">
        @include ('admin.includes._img_field', ['label' => 'JPG Image', 'ref_func' => 'jpg_media', 'formObj' => isset($formObj) ? $formObj : null, 'accept' => 'image/jpg, image/jpeg'])
    </div>
    
    @if (isset($artwork_type) && $artwork_type == 'print_ready')
    <div class="col-md-4">
        @include ('admin.includes._img_field', ['label' => 'PNG Image', 'ref_func' => 'png_media', 'formObj' => isset($formObj) ? $formObj : null, 'accept' => 'image/png'])
    </div>
    
    <div class="col-md-4">
        @include ('admin.includes._img_field', ['label' => 'PDF', 'ref_func' => 'pdf_media', 'formObj' => isset($formObj) ? $formObj : null, 'accept' => 'application/pdf'])
    </div>
    @endif */ ?>
    
    <?php /* <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('JPG URL') !!}
            {!! Form::text('jpg_url', null, ['class' => 'form-control', 'placeholder' => 'Enter JPG URL', 'required' => false]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('PNG URL') !!}
            {!! Form::text('png_url', null, ['class' => 'form-control', 'placeholder' => 'Enter PNG URL', 'required' => false]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('PDF URL') !!}
            {!! Form::text('pdf_url', null, ['class' => 'form-control', 'placeholder' => 'Enter PDF URL', 'required' => false]) !!}
        </div>
    </div> */ ?>
</div>
<hr />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Status') !!}
            {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' => 'form-control custom-select', 'required' => true]) !!}    
        </div>
    </div>
</div>
<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>

@push('page_scripts')
@endpush