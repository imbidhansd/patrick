@extends('company.profile_page.layout')

@section ('content')

<div class="container text-center pt-3 pb-3">
    <h1 class="company_name text-theme_color"><span>{{ Str::upper($companyObj->company_name) }}</span></h1>

    @if ($companyObj->status == 'Active')
    <h5 class="tagline blue">
        Official TrustPatrick.com Recommended Company
        &nbsp;
        <img src="{{ asset('/images/small-logo.png') }}" width="30" />
    </h5>
    @else
    @if (!Auth::guard('company_user')->check() || (Auth::guard('company_user')->check() && Auth::guard('company_user')->user()->company_id != $companyObj->id))
    <h5 class="tagline">
        <?php /* <i class="fas fa-exclamation-triangle"></i> */ ?>
        This business is not Official TrustPatrick.com Recommended Company
    </h5>
    @endif
    @endif

</div>


<div class="container">
    <div class="card-box">
        @include('company.profile_page._demo_profile_details')
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
        
        <div class="{{ $col1 }}">
            <div class="card-box eqHeightItem">
                <h3 class="title">About {{ $companyObj->company_name }}</h3>

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
                @include('company.profile_page._demo_company_gallery')
                @endif
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4" style="{{ $col2_display }}">
            @if ($companyObj->status == 'Active')
            <div class="card-box blue-font right-sidebar eqHeightItem">
                Official Recommended<br/>
                Company Since @if (!is_null($companyObj->registered_date)) {{ $companyObj->registered_date }} @endif
                <br/><br/>
                Most Recent Background<br/>
                Check 04/2020
                <br/><br/>
                {{ Str::upper($companyObj->company_name) }} performs Best Practices<br/>
                Background Checks on all employees
                <br/><br/>
                MAP Awards
                <br/><br/><br/><br/>
                Professional Affiliations
                <br/><br/>
            </div>
            @endif
        </div>
    </div>
</div>
<div class="clearfix"></div>



<div class="container" id="customer_reviews">
    <div class="card-box" >
        <ul class="nav nav-pills" role="tablist">
            <li class="nav-item">
                <a class="nav-link review_btn_link active" id="home-tab" data-toggle="pill" href="#reviews" role="tab" aria-controls="home" aria-selected="true">
                    Customer Reviews ({{ ((!is_null($average_ratings)) ? $average_ratings->total_reviews : 0) }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link complaint_btn_link" id="profile-tab" data-toggle="pill" href="#complaints" role="tab" aria-controls="profile" aria-selected="false">
                    Customer Complaints ({{ $total_complaints }})
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane blue-font show active" id="reviews" role="tabpanel" aria-labelledby="home-tab">

                @if (isset($latest_reviews) && count($latest_reviews) > 0)
                <div class="review_list">
                    @foreach ($latest_reviews AS $review_item)
                    <div class="review_item">
                        <h5>{{ $review_item->customer_name }}</h5>
                        <div class="stars{{ $review_item->feedback_id }}"></div>
                        <p>{!! $review_item->content !!}</p>
                    </div>
                    @endforeach
                </div>

                @else
                <p class="font-15 text-danger">{{ $companyObj->showCompanyName() }} has no reviews!</p>
                @endif

                <a href="javascript:;" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#submitReviewModal">Submit Review of {{ $companyObj->showCompanyName() }}</a>


            </div>
            <div class="tab-pane blue-font " id="complaints" role="tabpanel" aria-labelledby="profile-tab">


                @if (isset($latest_complaints) && count($latest_complaints) > 0)
                <div class="review_list">
                    @foreach ($latest_complaints AS $complaint_item)
                    <div class="review_item">
                        <h5>{{ $complaint_item->customer_name }}</h5>
                        <p>{!! $complaint_item->content !!}</p>
                    </div>
                    @endforeach
                </div>

                @else
                <p class="font-15 text-danger">{{ $companyObj->showCompanyName() }} has no complaints!</p>
                @endif

                <a href="javascript:;" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#submitComplaintModal">Submit Complaint of {{ $companyObj->showCompanyName() }}</a>


            </div>
        </div>    
    </div>
</div>
<div class="clearfix"></div>




<div class="container" id="service_provided">
    <div class="card-box" >

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
        <h3 class="title pb-3">Service Areas</h3>
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