@extends('company.profile_page.layout')

@section('title', $companyObj->company_name.' Reviews - TrustPatrick.com')
@section('meta')
<?php //<meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1"/> ?>
<meta name="robots" content="index, follow" />
<meta name="title" content="{{ $companyObj->company_name.' Reviews - TrustPatrick.com' }}" />
<meta name="keywords" content="" />
<meta name="description" content="{{ $companyObj->company_name.' reviews - TrustPatrick.com - Learn more about '.$companyObj->company_name.' - '.$meta_main_categories.' '.$companyObj->main_zipcode_city.' and '.$companyObj->state->name }}" />

    
<link rel="canonical" href="{{ url('/', ['company_slug' => $companyObj->slug]) }}" />


<meta property="og:url" content="{{ url('/', ['company_slug' => $companyObj->slug]) }}" />
<meta property="og:type" content="article" />
<meta property="og:title" content="{{ $companyObj->company_name }} | Trust Patrick" />
<?php /* <meta property="og:locale" content="en_US" /> */ ?>
@if ($companyObj->status == 'Active')
<meta property="og:description" content="{{ $companyObj->company_name }} - Official Trust Patrick Recommended Company providing {{ $main_categories }} serving {{ $companyObj->main_zipcode_city }}, {{ $companyObj->state->name }} and surrounding areas. {!! Str::words(strip_tags($companyObj->company_bio), 25, ' Read More') !!}" />
@else
<meta property="og:description" content="{{ $companyObj->company_name }} - Not an Offical Trust Patrick Recommended Company" />
@endif

<?php /* <meta property="og:site_name" content="Trust Patrick" /> */ ?>
@if (!is_null($companyObj->company_logo))
<meta property="og:image" content="https://s3.us-west-2.amazonaws.com/images.trustpatrick.com/20240113155245/trust_patrick_social_media_v2.jpg" />
@else
<meta property="og:image" content="https://s3.us-west-2.amazonaws.com/images.trustpatrick.com/trust_patrick_rn_logo_v1.png" />
@endif
<meta property="og:image:width" content="400" />
<meta property="og:image:height" content="200" />



<meta name="twitter:card" content="summary_large_image" />
@if ($companyObj->status == 'Active')
<meta name="twitter:description" content="{{ $companyObj->company_name }} - Official Trust Patrick Recommended Company providing {{ $main_categories }} serving {{ $companyObj->main_zipcode_city }}, {{ $companyObj->state->name }} and surrounding areas. {!! Str::words(strip_tags($companyObj->company_bio), 25, ' Read More') !!}" />
@else
<meta name="twitter:description" content="{{ $companyObj->company_name }} - Not an Offical Trust Patrick Recommended Company" />
@endif
<meta name="twitter:title" content="{{ $companyObj->company_name }} | Trust Patrick" />

@if (!is_null($companyObj->company_logo))
<meta name="twitter:image" content="{{ asset('/uploads/media/'.$companyObj->company_logo->file_name) }}" />
@else
<meta name="twitter:image" content="https://s3.us-west-2.amazonaws.com/images.trustpatrick.com/trust_patrick_rn_logo_v1.png" />
@endif

@if ($companyObj->status == 'Active')
<meta name="twitter:image:alt" content="{{ $companyObj->company_name }} - Offical Trust Patrick Recommended Company">
@else
<meta name="twitter:image:alt" content="{{ $companyObj->company_name }} - Not an offical Trust Patrick Recmmended Company">
@endif

@endsection

<?php /* @section('title', $companyObj->company_name.' Reviews - TrustPatrick.com')
@section('meta_title', $companyObj->company_name.' Reviews - TrustPatrick.com')
@section('meta_description', $companyObj->company_name.' reviews - TrustPatrick.com - Read reviews, see ratings and learn more about '.$companyObj->company_name.', '.$companyObj->city.', '.$companyObj->zipcode.'. '.$companyObj->company_name.' '.$companyObj->main_category->title) */ ?>

@section ('content')
<div class="container pt-3 text-left font-12">
    <div class="page-title-box">
        <div class="page-title-left">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="https://trustpatrick.com/">Home</a></li>
                <li class="breadcrumb-item active">{{ $companyObj->company_name }}</li>
            </ol>
        </div>
    </div>
</div>
<div class="container text-center">
    <h1 class="company_name text-theme_color pt-lg-5 pb-2"><span>{{ 
        Str::upper(is_null($companyObj->short_company_name) || 
                    empty(trim($companyObj->short_company_name)) ?
         $companyObj->company_name : 
         $companyObj->short_company_name) }}</span></h1>

    @if ($companyObj->status == 'Active')
    <h5 class="tagline blue pb-3">
        Official {{env('SITE_TITLE')}} Recommended Company
        &nbsp;
        <!-- <img src="{{ asset('/images/small-logo.png') }}" width="25" /> -->
        <img src="https://media.allaboutdriveways.com/20220917234624/verified_checkmark_v2.png" width="25" />
    </h5>
    @else
    @if (!Auth::guard('company_user')->check() || (Auth::guard('company_user')->check() && Auth::guard('company_user')->user()->company_id != $companyObj->id))
    <h5 class="tagline pb-3">
        <?php /* <i class="fas fa-exclamation-triangle"></i> */ ?>
        This business is not Official {{env('SITE_TITLE')}} Recommended Company
    </h5>
    @endif
    @endif

</div>


<div class="container">
    <div class="card-box pt-50px">
        @include('company.profile_page._profile_details')
    </div>
</div>
<div class="clearfix"></div>


<div class="container">
    <div class="row">
        @php
            $col1 = 'col-lg-12 col-md-12 col-sm-12';
            $gallery_cols = 'col-lg-8 col-md-8 col-sm-12 offset-lg-2 offset-md-2';
            $col2_display = 'display:none;';
        @endphp
        
        @if ($companyObj->status == 'Active')
            @php
                $col1 = 'col-lg-8 col-md-8 col-sm-8';
                $gallery_cols = 'col-lg-12 col-md-12 col-sm-12';
                $col2_display = '';
            @endphp
        @endif
        
        <div class="{{ $col1 }} {{ (($col2_display != '') ? 'xs-visible' : 'xs-hidden') }}">
            <div class="card-box eqHeightItem minHeight">
                <h2 class="title">About {{ $companyObj->company_name }}</h2>

                @if (!is_null($companyObj->company_approval_status) && $companyObj->company_approval_status->company_bio == 'in process')
                <p class="text-danger">Pending Review</p>
                @elseif (!is_null($companyObj->company_approval_status) && $companyObj->company_approval_status->company_bio == 'pending' && !is_null($companyObj->company_approval_status->company_bio_reject_note))
                <p class="text-danger">Pending Review</p>
                @elseif (!is_null($companyObj->company_approval_status) && $companyObj->company_approval_status->company_bio == 'completed')
                <div class="text-left">
                    {!! \App\Models\Custom::cleanHtml($companyObj->company_bio) !!}
                </div>
                @endif


                @if (isset($company_gallery) && count($company_gallery) > 0)
                <div class="clearfix">&nbsp;</div>
                @include('company.profile_page._company_gallery')
                @endif
            </div>
        </div>
        
        <div class="col-lg-4 col-md-4 col-sm-4" style="{{ $col2_display }}">
            @if ($companyObj->status == 'Active')
            <div class="card-box blue-font right-sidebar eqHeightItem">

                @if (!is_null($companyObj->approval_date))
                Official Recommended<br/>
                Company Since {{ \Carbon\Carbon::createFromFormat('Y-m-d', $companyObj->approval_date)->format('m/Y') }}
                <br/><br/>
                @endif

                
                <?php /* @if (!is_null($recent_bg_check))
                Most Recent Background<br/>
                Check {{ \App\Models\Custom::date_formats($recent_bg_check->bg_check_date, env('DB_DATE_FORMAT'), env('BG_DATE_FORMAT')) }}
                <br/><br/>
                @else */ ?> 
                
                @if (!is_null($companyObj->bg_check_date))
                Most Recent Background<br/>
                Check {{ \App\Models\Custom::date_formats($companyObj->bg_check_date, env('DB_DATE_FORMAT'), env('BG_DATE_FORMAT')) }}
                <br/><br/>
                @elseif (!is_null($companyObj->approval_date))
                Most Recent Background<br/>
                Check {{ \App\Models\Custom::date_formats($companyObj->approval_date, env('DB_DATE_FORMAT'), env('BG_DATE_FORMAT')) }}
                <br/><br/>
                @endif
                

                @if ($companyObj->in_home_service == 'yes')
                <span class="font-12">
                {{ Str::upper($companyObj->company_name) }} performs Best Practices<br/>
                Background Checks on all employees
                </span>
                @endif
                <br/><br/>
                <?php /* MAP Awards
                <br/><br/><br/><br/>
                Professional Affiliations
                <br/><br/> */ ?>
            </div>
            @endif
        </div>
        
        <div class="{{ $col1 }} {{ (($col2_display != '') ? 'xs-hidden' : 'xs-visible') }}">
            <div class="card-box eqHeightItem minHeight">
                <h2 class="title">About {{ $companyObj->company_name }}</h2>

                @if (!is_null($companyObj->company_approval_status) && $companyObj->company_approval_status->company_bio == 'in process')
                <p class="text-danger">Pending Review</p>
                @elseif (!is_null($companyObj->company_approval_status) && $companyObj->company_approval_status->company_bio == 'pending' && !is_null($companyObj->company_approval_status->company_bio_reject_note))
                <p class="text-danger">Pending Review</p>
                @elseif (!is_null($companyObj->company_approval_status) && $companyObj->company_approval_status->company_bio == 'completed')
                <div class="text-left">
                    {!! \App\Models\Custom::cleanHtml($companyObj->company_bio) !!}
                </div>
                @endif


                @if (isset($company_gallery) && count($company_gallery) > 0)
                <div class="clearfix">&nbsp;</div>
                @include('company.profile_page._company_gallery')
                @endif
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>



<div class="container" id="customer_reviews">
    <div class="card-box minHeight">
        <h2 class="title pb-3">{{ $companyObj->company_name }} Reviews</h2>
        
        <ul class="nav nav-pills" role="tablist">
            <li class="nav-item">
                <a class="nav-link review_btn_link active" id="home-tab" data-toggle="pill" href="#reviews" role="tab" aria-controls="home" aria-selected="true">
                    Customer Reviews {{ ((!is_null($average_ratings)) ? $average_ratings->total_reviews : 0) }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link complaint_btn_link" id="profile-tab" data-toggle="pill" href="#complaints" role="tab" aria-controls="profile" aria-selected="false">
                    Customer Complaints {{ $total_complaints }}
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane blue-font show active" id="reviews" role="tabpanel" aria-labelledby="home-tab">

                <a href="javascript:;" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#submitReviewModal">Submit Review of {{ $companyObj->showCompanyName() }}</a>
                
                <div class="clearfix">&nbsp;</div>
                @if (isset($latest_reviews) && count($latest_reviews) > 0)
                <div class="review_list">
                    @foreach ($latest_reviews AS $review_item)
                    <div class="review_item">
                        <h5>{{ $review_item->customer_name }}</h5>
                        <div class="stars{{ $review_item->feedback_id }}"></div>
                        <p>{!! $review_item->content !!}</p>
                        
                        @if (count($review_item->feedback_files) > 0)
                        <div class="form-group">
                            <div class="row">
                                @foreach($review_item->feedback_files AS $files)
                                @if(!is_null($files->media))
                                <div class="col-md-1">
                                    <div class="media_box">
                                        <a href="{{ asset('/') }}uploads/media/{{ $files->media->file_name }}" data-fancybox="gallery">

                                            @if ($files->media->file_type == 'application/pdf')
                                            <i class="far fa-file-pdf font-40"></i>
                                            @else
                                            <img src="{{ asset('/') }}uploads/media/fit_thumbs/50x50/{{ $files->media->file_name }}" class='img-thumbnail' />
                                            @endif

                                        </a>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                @else
                <p class="font-15 text-danger">{{ $companyObj->showCompanyName() }} has no reviews!</p>
                @endif
            </div>
            <div class="tab-pane blue-font " id="complaints" role="tabpanel" aria-labelledby="profile-tab">
                <a href="javascript:;" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#submitComplaintModal">Submit Complaint of {{ $companyObj->showCompanyName() }}</a>

                <div class="clearfix">&nbsp;</div>
                @if (isset($latest_complaints) && count($latest_complaints) > 0)
                <div class="review_list">
                    @foreach ($latest_complaints AS $complaint_item)
                    <div class="review_item">
                        <h5>{{ $complaint_item->customer_name }}</h5>
                        <p>{!! $complaint_item->content !!}</p>
                        
                        @if (count($complaint_item->complaint_files) > 0)
                        <div class="form-group">
                            <div class="row">
                                @foreach($complaint_item->complaint_files AS $files)
                                @if(!is_null($files->media))
                                <div class="col-md-1">
                                    <div class="media_box">
                                        <a href="{{ asset('/') }}uploads/media/{{ $files->media->file_name }}" data-fancybox="gallery">

                                            @if ($files->media->file_type == 'application/pdf')
                                            <i class="far fa-file-pdf font-40"></i>
                                            @else
                                            <img src="{{ asset('/') }}uploads/media/fit_thumbs/50x50/{{ $files->media->file_name }}" class='img-thumbnail' />
                                            @endif

                                        </a>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                @else
                <p class="font-15 text-danger">{{ $companyObj->showCompanyName() }} has no complaints!</p>
                @endif
            </div>
        </div>    
    </div>
</div>
<div class="clearfix"></div>




<div class="container" id="service_provided">
    <div class="card-box pb-5">
        <h3 class="title pb-3">Service Provided</h3>

        <div class="row">
            @if (isset($company_service_category_list) && count($company_service_category_list) > 0)
            @include('company.profile_page._service_category_list')
            @endif
        </div>
    </div>
</div>

<div class="clearfix"></div>


<div class="container" id="service_areas">
    <div class="card-box">
        <h4 class="title pb-3">Service Areas</h4>
        @if (isset($company_service_areas) && count($company_service_areas) > 0)
        @include('company.profile_page._service_areas_list')
        @endif
    </div>
</div>

<div class="clearfix"></div>


<div class="container">
    <div class="card-box">
        @include('company.profile_page._company_generate_lead_form')
        <div class="clearfix"></div>
    </div>
</div>


@include('company.profile_page._company_review_form')
@include('company.profile_page._company_complaint_form')
@endsection


@section('page_js')
@include('company.profile_page._profile_page_js')
@stack('page_script')

<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '140924384014500');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=140924384014500&ev=PageView&noscript=1"
/></noscript>
<!-- DO NOT MODIFY -->
<!-- End Facebook Pixel Code -->

<script type="text/javascript">
    $(function () {
        /* Submit lead start */
        $("#create_request").on("click", function () {
            @if (Auth::guard('company_user')->check() && Auth::guard('company_user')->user()->company_id == $companyObj->id)
                Swal.fire({
                    title: '',
                    type: 'info',
                    text: 'When consumers submit a request to be contacted by your company, you will be notified immediately via email. No other companies receive this.'
                }).then(function (t){
                    $('html, body').animate({
                        scrollTop: $("#generate_lead_form").offset().top - parseInt($("header .nav_sec").height())
                    }, 2000);
                });
            @else
            $('html, body').animate({
                scrollTop: $("#generate_lead_form").offset().top - parseInt($("header .nav_sec").height())
            }, 2000);
            @endif
        });


        $(".scroll_btn").on("click", function (){
            var div_id = $(this).data("href");
            $('html, body').animate({
                scrollTop: $(div_id).offset().top - parseInt($("header .nav_sec").height())
            }, 2000);
        });
    });
</script>
@endsection