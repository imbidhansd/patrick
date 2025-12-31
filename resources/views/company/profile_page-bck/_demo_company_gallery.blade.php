<div class="row mt-4">
    <?php //<div class="col-lg-8 offset-lg-2"> ?>
    <div class="{{ $gallery_cols }}">


        <div class="owl-carousel owl-theme">
            @foreach ($company_gallery AS $company_gallery_item)
            @if (!is_null($company_gallery_item->media))
            <div class="item">
                <a data-fancybox="gallery" href="{{ asset('/uploads/media/'.$company_gallery_item->media->file_name) }}">
                    <img src="{{ asset('/uploads/media/fit_thumbs/900x500/'.$company_gallery_item->media->file_name) }}" alt="slide-image" />
                </a>
            </div>
            @endif
            @endforeach
        </div>


        <?php /*

        <ul class="bxslider property-slider">
            @foreach ($company_gallery AS $company_gallery_item)
            @if (!is_null($company_gallery_item->media))
            <li>
                <a data-fancybox="gallery" href="{{ asset('/uploads/media/'.$company_gallery_item->media->file_name) }}">
                    <img src="{{ asset('/uploads/media/fit_thumbs/900x500/'.$company_gallery_item->media->file_name) }}" alt="slide-image" />
                </a>
            </li>
            @endif
            @endforeach
        </ul>

        <div id="bx-pager" class="text-center hide-phone">
            @foreach ($company_gallery AS $i => $company_gallery_item)
            @if (!is_null($company_gallery_item->media))
            <a data-slide-index="{{ $i }}" href=""><img src="{{ asset('/uploads/media/fit_thumbs/100x100/'.$company_gallery_item->media->file_name) }}" class="img-thumbnail mb-1" alt="slide-image" height="70" /></a>
            @endif
            @endforeach
        </div> */ ?>

    </div>
</div>


@push('page_script')
<?php /*<link href="{{ asset('/themes/admin/assets/libs/bxslider/jquery.bxslider.min.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('/themes/admin/assets/libs/bxslider/jquery.bxslider.min.js') }}"></script>

<script type="text/javascript">
    $(function () {
        $(".property-slider").bxSlider({
            pagerCustom: "#bx-pager"
        });
    });
</script>*/ ?>


<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js"></script>

<script type="text/javascript">
    $(function(){

        $('.eqHeightItem').matchHeight();


        $('.owl-carousel').owlCarousel({
            loop:true,
            margin:10,
            nav:true,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:3
                },
                1000:{
                    items:4
                }
            }
        })
    });
</script>


@endpush