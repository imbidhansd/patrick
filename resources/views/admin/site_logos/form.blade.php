<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter Title', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Size') !!}
            {!! Form::text('size', null, ['class' => 'form-control', 'placeholder' => 'Enter Size', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Banner For') !!}
            {!! Form::select('banner_for', array_combine($banner_for_options,$banner_for_options), null, ['class' => 'form-control custom-select', 'placeholder' => 'Select', 'required' => true, 'id' => 'banner_for']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Banner URL') !!}
            {!! Form::text('banner_url', null, ['class' => 'form-control', 'placeholder' => 'Enter Banner URL', 'required' => true]) !!}
        </div>
    </div>

    <?php /*
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Media Type') !!}
            {!! Form::select('media_type', $mediaTypeArr, null, ['class' => 'form-control custom-select', 'placeholder' => 'Select Media type', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        @include ('admin.includes._img_field', ['label' => 'Media', 'ref_func' => 'media','formObj' => isset($formObj) ? $formObj : null])
    </div>
    */ ?>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Banner Alternate Text') !!}
            {!! Form::text('banner_alt', null, ['class' => 'form-control', 'placeholder' => 'Enter Alternate Text', 'required' => true, 'id' => 'banner_alt']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Status') !!}
            <div class="select">
                {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' => 'form-control custom-select', 'required' => 'required']) !!}
                <div class="select__arrow"></div>
            </div>
        </div>
    </div>
</div>


<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>

@push('page_scripts')
<script>
function generateBannerAlt( bannerFor) {    
    let transformedDomainSlug = "{{ $banner_domain_fullname }}";

    const bannerForLowerCase = bannerFor.toLowerCase();

    return `${transformedDomainSlug} ${bannerForLowerCase} banner`;
}
$(document).ready(function() {
    $('#banner_for').on('change', function() {
        const bannerFor = $(this).val(); // Get the value of the banner_url input

        // Call the JavaScript function
        const bannerAlt = generateBannerAlt(bannerFor);

        // Assuming you have an input field with the id 'banner_alt'
        if ($('#banner_alt').val().trim() === '') {
            $('#banner_alt').val(bannerAlt);
        }
    });
});
</script>
@endpush
