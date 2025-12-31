<div class="row mt-4">
    <?php //<div class="col-lg-8 offset-lg-2"> ?>
    <div class="{{ $gallery_cols }}">

        @php
            $gallary_shown = false;
            $gallary_col_class = '';
        @endphp

        @if($company_gallery->count() >= 4)
        @php
            $gallary_shown = true;
        @endphp
        <div class="owl-carousel owl-theme">
            @foreach ($company_gallery AS $company_gallery_item)
                @if (!is_null($company_gallery_item->media))
                    @if ($company_gallery_item->gallery_type == 'image')    
                        <div class="item">
                            <a data-fancybox="gallery" href="{{ asset('/uploads/media/'.$company_gallery_item->media->file_name) }}">
                                <img src="{{ asset('/uploads/media/fit_thumbs/900x500/'.$company_gallery_item->media->file_name) }}" alt="slide-image" />
                            </a>
                        </div>
                    @else
                        @if ($company_gallery_item->video_type == 'vimeo')
                        @php $video_link = 'https://vimeo.com/'.$company_gallery_item->video_id; @endphp
                        @elseif ($company_gallery_item->video_type == 'youtube')
                        @php $video_link = 'https://www.youtube.com/watch?v='.$company_gallery_item->video_id; @endphp
                        @endif
                        <div class="item">
                            <a data-fancybox="gallery" href="{{ $video_link }}">
                                <img src="{{ asset('/uploads/media/fit_thumbs/900x500/'.$company_gallery_item->media->file_name) }}" alt="slide-image" />
                            </a>
                        </div>
                    @endif
                @endif
            @endforeach
        </div>
        @elseif ($company_gallery->count() == 3)

        @php
            $gallary_col_class = 'col-md-4';
        @endphp

        @elseif ($company_gallery->count() == 2)
        @php
            $gallary_col_class = 'col-md-6';
        @endphp
        @elseif ($company_gallery->count() == 1)
        @php
            $gallary_col_class = 'col-md-12';
        @endphp    
        @endif



        @if (!$gallary_shown)
        <div class="row">
            @foreach ($company_gallery AS $company_gallery_item)
                @if ($company_gallery_item->gallery_type == 'image')
                    @if (!is_null($company_gallery_item->media))
                    <div class="{{ $gallary_col_class }}">
                        <a data-fancybox="gallery" href="{{ asset('/uploads/media/'.$company_gallery_item->media->file_name) }}">
                            <img src="{{ asset('/uploads/media/fit_thumbs/900x500/'.$company_gallery_item->media->file_name) }}" alt="slide-image" class="w-100 img-responsive" />
                        </a>
                    </div>
                    @endif
                @else
                    @if ($company_gallery_item->video_type == 'vimeo')
                    @php $video_link = 'https://vimeo.com/'.$company_gallery_item->video_id; @endphp
                    @elseif ($company_gallery_item->video_type == 'youtube')
                    @php $video_link = 'https://www.youtube.com/watch?v='.$company_gallery_item->video_id; @endphp
                    @endif
                    <div class="{{ $gallary_col_class }}">
                        <a data-fancybox="gallery" href="{{ $video_link }}">
                            <img src="{{ $company_gallery_item->image_link }}" alt="slide-image" class="w-100 img-responsive" />
                        </a>
                    </div>
                @endif
            @endforeach
        </div>

        @endif



    </div>
</div>


@push('page_script')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js"></script>

<script type="text/javascript">
    $(function(){
        $('.eqHeightItem').matchHeight();

        $('.owl-carousel').owlCarousel({
            loop:false,
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