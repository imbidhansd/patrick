@extends('company.layout-without-sidebar')

@section ('content')
<?php /* @include('admin.includes.breadcrumb') */ ?>

@include('admin.includes.formErrors')
@include('flash::message')

<div class="card-box">
    <div class="text-center">
        <h2>{{ $companyObj->company_name }}</h2>
        @if ($companyObj->status == 'Active')
        <h4>
            Official TrustPatrick.com Recommended Company
            &nbsp;
            <img src="{{ asset('/images/small-logo.png') }}" width="30" />
        </h4>
        @endif
    </div>

    <div class="clearfix">&nbsp;</div> 

    @include('company.profile_page._company_profile_details')
    
    
    @if (isset($feedback) && count($feedback) > 0)
    <div class="timeline timeline-left complaint_response_timeline">
        <article class="timeline-item timeline-item-left">
            <div class="text-left">
                <div class="time-show first">
                    <a href="#" class="btn btn-primary width-lg">Customer Reviews ({{ ((!is_null($average_ratings)) ? $average_ratings->total_reviews : 0) }})</a>
                </div>
            </div>
        </article>
        @foreach ($feedback AS $feedback_item)
        <article class="timeline-item ">
            <div class="timeline-desk">
                <div class="panel">
                    <div class="timeline-box">
                        <span class="arrow"></span>
                        <span class="timeline-icon bg-{{\App\Models\Custom::feedback_status_color($feedback_item->feedback_status) }}"><i class="mdi mdi-checkbox-blank-circle-outline"></i></span>

                        <h4 class="text-{{\App\Models\Custom::feedback_status_color($feedback_item->feedback_status) }}">{{ $feedback_item->customer_name }}</h4>
                        <p class="timeline-date text-muted"><small>{{ $feedback_item->created_at->format(env('DATE_FORMAT')) }}</small></p>
                        <div class="text-left">
                            <div class="short_content">
                                <p>
                                    {!! Str::limit($feedback_item->content, 200, '...') !!}
                                    <a href="javascript:;" class="read_full_content">Read More</a>
                                </p>
                            </div>

                            <div class="full_content" style="display: none;">
                                {!! $feedback_item->content !!}

                                @if (count($feedback_item->feedback_files) > 0)
                                <div class="clearfix">&nbsp;</div>
                                <b>File(s): </b> <br />

                                <div class="form-group">
                                    <div class="row">
                                        @foreach($feedback_item->feedback_files AS
                                        $files)
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

                                <a href="javascript:;" class="read_less_content">Read Less</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </article>
        @endforeach
    </div>
    
    <div class="float-left">
        {!! $feedback->render() !!}
    </div>
    @else
    <div class="text-left">No review found.</div>
    @endif
</div>
@endsection


@section('page_js')
@include('company.profile_page._profile_page_js')
@stack('page_script')
<script type="text/javascript">
$(function () {
    $(".read_full_content").on("click", function () {
        $(this).parents(".text-left").find(".short_content").hide();
        $(this).parents(".text-left").find(".full_content").show();
    });
            
    $(".read_less_content").on("click", function () {
        $(this).parents(".text-left").find(".short_content").show();
        $(this).parents(".text-left").find(".full_content").hide();
    });
});
</script>
@endsection