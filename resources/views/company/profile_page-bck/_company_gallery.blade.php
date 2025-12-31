<div class="col-lg-8">
    <ul class="bxslider property-slider">
        @foreach ($company_gallery AS $company_gallery_item)
        @if (!is_null($company_gallery_item->media))
        <li><img src="{{ asset('/uploads/media/fit_thumbs/900x500/'.$company_gallery_item->media->file_name) }}" alt="slide-image" /></li>
        @endif
        @endforeach
    </ul>

    <div id="bx-pager" class="text-center hide-phone">
        @foreach ($company_gallery AS $i => $company_gallery_item)
        @if (!is_null($company_gallery_item->media))
        <a data-slide-index="{{ $i }}" href=""><img src="{{ asset('/uploads/media/fit_thumbs/100x100/'.$company_gallery_item->media->file_name) }}" class="img-thumbnail mb-1" alt="slide-image" height="70" /></a>
        @endif
        @endforeach
    </div>

    <div class="clearfix">&nbsp;</div>
    <div class="clearfix">&nbsp;</div>
</div>

@push('page_script')
<link href="{{ asset('/themes/admin/assets/libs/bxslider/jquery.bxslider.min.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('/themes/admin/assets/libs/bxslider/jquery.bxslider.min.js') }}"></script>

<script type="text/javascript">
$(function () {
    $(".property-slider").bxSlider({
        pagerCustom: "#bx-pager"
    });
});
</script>
@endpush