<?php
$admin_page_title = 'Dashboard';
?>
@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="row">
    <div class="col-lg-9 col-md-8 col-sm-12">
        @php        
        $company_owner = Auth::guard('company_user')->user();        
        @endphp


        @if ($company_item->company_subscribe_status == 'unsubscribed' && $company_item->membership_level->paid_members == 'no')
        <div id="welcome_block">
            <div class="card text-center">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10 offset-md-1 col-sm-12">

                            <div class="alert alert-danger">
                                <h5>
                                    You are currently {{ $company_item->company_subscribe_status }} and not receiving preview leads.
                                    <br/>
                                    Resubscribe to continue receiving preview leads.
                                </h5>

                            </div>

                            <h5 class="text-danger"></h5>

                            <a href="javascript:;" data-type="subscribe" class="btn btn-sm btn-primary change_subscription">Resubscribe</a>

                            <h4>OR</h4>

                            <div class="row">
                                @if ($company_item->membership_level->paid_members == 'no')
                                <div class="col-md-6 col-sm-6">
                                    <h6 class="text-info">Upgrade now and unlock all preview leads</h6>
                                    <a href="{{ url('referral-list/application-process') }}" class="btn btn-sm btn_upgrade">Upgrade Now</a>
                                </div>
                                @endif
                                <div class="col-md-6 col-sm-6">
                                    <h6 class="text-info">Call Member Support at <a href="tel: 720-445-4400" class="text-danger">720-445-4400</a></h6>
                                    <a href="https://opp.trustpatrick.com/#works" class="btn btn-sm btn-primary"><i
                                            class="fas fa-exclamation-circle"></i> Learn More</a>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-2">&nbsp;</div>
                    </div>
                </div>
            </div>
        </div>
        @endif


        @if ($company_item->status == 'Final Review')
        <div id="welcome_block">
            <div class="card text-center">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10 offset-md-1 col-sm-12">
                            <h3 class="m-0">Welcome {{ $company_owner->company->company_name }}</h3>

                            <div class="clearfix">&nbsp;</div>
                            <h4>Your Application is under Final Review. Please wait.</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @elseif ($company_item->status == 'Approved')
        <div id="welcome_block">
            <div class="card text-center">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10 offset-md-1 col-sm-12">
                            <h4>Congratulations! Your application has been approved.</h4>
                            <?php /* <a href="{{ url('billing') }}" class="btn btn-primary btn-sm">Pay Now</a> */ ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @elseif ($company_item->status == 'Active' && isset($leads_pie_chart) && $leads_pie_chart != '')
        <div class="card-box">
            <?php /* <h3 class="m-0">Welcome {{ $company_owner->company->company_name }}</h3> */ ?>
            <div id="pie_chart" style="width: 100%; height: 400px; margin: 0 auto"></div>
        </div>
        @endif


        @if (!is_null($membership_video) && !is_null($membership_video->video_id) && is_null($hide_company_video))
        <div class="card-box p-0">
            <a href="javascript:;" class="btn-sm p-0 text-dark float-right dismiss_video" title="Dismiss from Dashboard"><i class="fas fa-window-close"></i></a>
            <div class="text-center">
                <div class="clearfix">&nbsp;</div>
                <div class="row">
                    <div class="col-md-10 offset-md-1 col-sm-12">
                        <div class="embed-container embed-container-aspect-ratio">
                            <iframe src="https://player.vimeo.com/video/{{ $membership_video->video_id }}" width="100%" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
                <div class="clearfix">&nbsp;</div>
            </div>
        </div>
        @endif


        <?php /* Chart Statisticks [Start] */ ?>

        <?php /* Chart Statisticks [End] */ ?>

        @if (isset($news) && count($news) > 0)

        
        <?php // <h4>Latest News </h4> ?>

        @foreach ($news AS $news_item)
        <div class="card news_section">
            <div class="card-header bg-secondary text-white">
                <div class="card-widgets">
                    <i class="far fa-calendar-alt"></i> &nbsp; {{ $news_item->date }}
                </div>

                <h3 class="card-title text-white mb-0">
                    <i class="far fa-newspaper"></i> {{ $news_item->title }}
                </h3>
            </div>
            <div class="card-body">
                <div class="text-left">
                    <div class="short_content">
                        <p>
                            {!! Str::limit($news_item->short_content, 200, '...') !!}
                            <a href="javascript:;" class="read_full_content">Read More</a>
                        </p>
                    </div>

                    <div class="full_content" style="display: none;">
                        {!! $news_item->content!!}
                        <a href="javascript:;" class="read_less_content">Read Less</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach


        @endif
    </div>    
    @include('company.profile._company_profile_sidebar')
</div>
@endsection


@section ('page_js')
@include('company.profile._js')

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
@if (isset($leads_pie_chart) && $leads_pie_chart != '')
    google.charts.load('current', {packages: ['corechart']});
    function drawChart() {
        // Define the chart to be drawn.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Browser');
        data.addColumn('number', 'Percentage');
        data.addRows([{!! rtrim($leads_pie_chart, ',') !!}]);

        // Set chart options
        var options = {
            'title': 'Leads Summary Category Wise',
            is3D: true
        };

        // Instantiate and draw the chart.
        var chart = new google.visualization.PieChart(document.getElementById('pie_chart'));
        chart.draw(data, options);
    }
    google.charts.setOnLoadCallback(drawChart);
@endif
</script>


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
    
    $("#close_btn").on("click", function () {
        $(this).parents("#welcome_block").remove();
    });
});
</script>
<style>  
.modal-box {
  display: none;
  position: absolute;
  z-index: 1000;
  width: 98%;
  background: white;
  border-bottom: 1px solid #aaa;
  border-radius: 4px;
  box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
  border: 1px solid rgba(0, 0, 0, 0.1);
  background-clip: padding-box;
}
@media (min-width: 32em) {
.modal-box { width: 50%; }
}
.modal-box header,
.modal-box .modal-header {
  padding: 1.25em 1.5em;
  border-bottom: 1px solid #ddd;
}
.modal-box header h3,
.modal-box header h4,
.modal-box .modal-header h3,
.modal-box .modal-header h4 { margin: 0; }
.modal-box .modal-body { padding: 1.5em 1.5em; }
.modal-box footer,
.modal-box .modal-footer {
  padding: 1em;
  border-top: 1px solid #ddd;
  background: rgba(0, 0, 0, 0.02);
  text-align: right;
}
.modal-overlay {
  opacity: 0;
  filter: alpha(opacity=0);
  position: absolute;
  top: 0;
  left: 0;
  z-index: 900;
  width: 100%;
  height: 100%;
  background: rgb(0 0 0) !important;
}
a.close {
  line-height: 1;
  font-size: 1.5em;
  position: absolute;
  top: 5%;
  right: 2%;
  text-decoration: none;
  color: #bbb;
}
a.close:hover {
  color: #222;
  -webkit-transition: color 1s ease;
  -moz-transition: color 1s ease;
  transition: color 1s ease;
}
</style>
{{-- 
<div class="row">
    <div id="profile_popup" class="modal-box">
    <header> <a href="#" class="js-modal-close close">×</a>
        <h3 class="m-0">Welcome {{ $company_owner->company->company_name }}</h3>
    </header>
    <div class="modal-body">       
        <div class="row">
            <div class="col-md-12">
                <div class="form-group radio_update_comp_info_grp">
                    {!! Form::label('Please update your company information.') !!}
                    <div class="radio radio-primary radio-circle">
                        <input type="radio" checked="true" name="radio_update_comp_info" class="radio_update_comp_info" value="i_am_owner" id="radio_i_am_owner"/>
                        <label for="radio_i_am_owner">I am the owner of the business.</label>
                    </div>
                    <div class="radio radio-primary radio-circle">
                        <input type="radio" name="radio_update_comp_info" class="radio_update_comp_info" value="i_am_business" id="radio_i_am_business"/>
                        <label for="radio_i_am_business">I am a representative of this business and will be managing the listing.</label>
                    </div>
                </div>
            </div>            
        </div>
        <div class="row">
            <div class="col-md-12">
                <h5>Company Owner Information</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    {!! Form::label('Company Owner First & Last Name') !!}
                    {!! Form::text('comp_owner_name', $company_owner->first_name.' '.$company_owner->last_name, ['class' => 'form-control', 'placeholder' => 'Enter Company Owner Name', 'required' => true]) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    {!! Form::label('Company Owner Email Address') !!}
                    {!! Form::text('comp_owner_email', $company_owner->email, ['class' => 'form-control', 'placeholder' => 'Enter Company Owner Email Address', 'required' => true]) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    {!! Form::label('Confirm Company Owner Email Address') !!}
                    {!! Form::text('confirm_comp_owner_email', $company_owner->email, ['class' => 'form-control', 'placeholder' => 'Confirm Company Owner Email Address', 'required' => true]) !!}
                </div>
            </div> 
        </div>        
        <div class="row hide rep-sec">
            <div class="col-md-12">
                <h5>Company Representative Information</h5>
            </div>
        </div>
        <div class="row hide rep-sec">
            <div class="col-md-8">
                <div class="form-group">
                    {!! Form::label('Company Representative First & Last Name') !!}
                    {!! Form::text('comp_rep_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Company Representative Name', 'required' => true]) !!}
                </div>
            </div>
        </div>
        <div class="row hide rep-sec">
            <div class="col-md-8">
                <div class="form-group">
                    {!! Form::label('Company Representative Email Address') !!}
                    {!! Form::text('comp_rep_email', null, ['class' => 'form-control', 'placeholder' => 'Enter Company Representative Email Address', 'required' => true]) !!}
                </div>
            </div>
        </div>
        <div class="row hide rep-sec">
            <div class="col-md-8">
                <div class="form-group">
                    {!! Form::label('Confirm Company Representative Email Address') !!}
                    {!! Form::text('confirm_comp_rep_email', null, ['class' => 'form-control', 'placeholder' => 'Confirm Company Representative Email Address', 'required' => true]) !!}
                </div>
            </div> 
        </div>
        <div class="row"> 
            <div class="col-md-8">
                <div class="form-group">
                    {!! Form::label('New Password') !!}
                    <div class="input-group">                        
                        {!! Form::password('comp_passwd', ['class' => 'form-control', 'data-parsley-uppercase' => 1, 'data-parsley-lowercase' => 1, 'data-parsley-number' => 1, 'data-parsley-special' => 1, 'data-parsley-minlength' => 6, 'data-parsley-maxlength' => 50, 'required' => true]) !!}
                        <span class="input-group-append view-password">
                            <button type="button" class="btn btn-info"><i class="fas fa-eye"></i></button>
                        </span>
                    </div>                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    {!! Form::label('Confirm Password') !!}
                    <div class="input-group">                        
                        {!! Form::password('confirm_comp_passwd', ['class' => 'form-control', 'data-parsley-uppercase' => 1, 'data-parsley-lowercase' => 1, 'data-parsley-number' => 1, 'data-parsley-special' => 1, 'data-parsley-minlength' => 6, 'data-parsley-maxlength' => 50, 'required' => true]) !!}
                        <span class="input-group-append view-password">
                            <button type="button" class="btn btn-info"><i class="fas fa-eye"></i></button>
                        </span>
                    </div>                    
                </div>
            </div>  
        </div>
    </div>
    <footer> 
        <a href="#" class="btn btn-primary btn-sm js-modal-update">Update</a> 
        <a href="#" class="btn btn-primary btn-sm js-modal-close">Close</a> 
    </footer>
    </div>
</div>
--}}
<script>
$(function(){
    // $("body").append("<div class='modal-overlay'></div>");
    // $(".modal-overlay").fadeTo(500, 0.7);
    // $('#profile_popup').fadeIn();    
    // $(".js-modal-close").click(function() {
    //     $(".modal-box, .modal-overlay").fadeOut(500, function() {
    //         $(".modal-overlay").remove();
    //     }); 
    // }); 
    // $(".js-modal-update").click(function() {
    //     alert("Updating the data..");
    //     $(".modal-box, .modal-overlay").fadeOut(500, function() {
    //         $(".modal-overlay").remove();
    //     }); 
    // });
    $(window).resize(function() {
        $(".modal-box").css({
            top: 100 /*($(window).height() - $(".modal-box").outerHeight()) / 2*/,
            left: ($(window).width() - $(".modal-box").outerWidth()) / 2,
            height : $(window).height() - 150,
            'overflow-x' : 'hidden',
            'overflow-y' : 'scroll'
        });
    }); 
    $(window).resize(); 
    $('.radio_update_comp_info_grp input[type="radio"]').click(function(){
    	if($(this).val() == "i_am_business")
        {
            $(".rep-sec").removeClass("hide");
        }
        else
        {
            $(".rep-sec").addClass("hide");
        }
    });
    $('.view-password').mousedown(function(){
        $(this).closest('.input-group').find('input').attr('type','text');
    });
    $('.view-password').mouseup(function(){
        $(this).closest('.input-group').find('input').attr('type','password');
    });
});
</script>
@endsection
