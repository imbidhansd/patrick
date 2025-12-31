<?php
    $admin_page_title = 'Dashboard';
?>
@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="row text-center">

    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
        <div class="card-box widget-box-one">
            <div class="wigdet-one-content">
                <p class="m-0 text-uppercase text-overflow">Today Leads</p>
                <h2 class="text-danger"><span data-plugin="counterup">16</span></h2>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
        <div class="card-box widget-box-one">
            <div class="wigdet-one-content">
                <p class="m-0 text-uppercase text-overflow">Last 7 days Leads</p>
                <h2 class="text-dark"><span data-plugin="counterup">89</span></h2>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
        <div class="card-box widget-box-one">
            <div class="wigdet-one-content">
                <p class="m-0 text-uppercase text-overflow">Last 30 days Leads</p>
                <h2 class="text-warning"><span data-plugin="counterup">207</span></h2>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
        <div class="card-box widget-box-one">
            <div class="wigdet-one-content">
                <p class="m-0 text-uppercase text-overflow">All Time</p>
                <h2 class="text-success"><span data-plugin="counterup">656</span></h2>
            </div>
        </div>
    </div>
    <!-- end col -->
</div>


<div class="row">
    <div class="col-lg-9 col-md-8 col-sm-12">


        @php
        $company_owner = Auth::guard('company_user')->user();
        @endphp


        @if ($company_item->company_subscribe_status == 'unsubscribed')
        <div id="welcome_block">
            <div class="card text-center">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10 offset-md-1 col-sm-12">
                            <h3 class="m-0">Welcome {{ $company_owner->company->company_name }}</h3>

                            <div class="alert alert-danger">
                                You are currently
                                {{ $company_item->company_subscribe_status }}
                                and not receiving
                                preview leads or free leads submitted from your company page Resubscribe to continue
                                receiving preview leads and free leads.
                            </div>


                            <h5 class="text-danger"></h5>

                            <a href="javascript:;" data-type="subscribe"
                                class="btn btn-sm btn-primary change_subscription">Resubscribe</a>

                            <h4>OR</h4>

                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <h6 class="text-info">Upgrade now and unlock all preview leads</h6>
                                    <a href="javascript:;" class="btn btn-sm btn-info">Upgrade
                                        Now</a>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <h6 class="text-info">Call Member Support at <a href="tel: 720-445-4400"
                                            class="text-danger">720-445-4400</a></h6>
                                    <a href="javascript:;" class="btn btn-sm btn-primary"><i
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
                            <h3 class="m-0">Welcome {{ $company_owner->company->company_name }}</h3>

                            <div class="clearfix">&nbsp;</div>
                            <h4>Your Application has been Approved. To activate your application kindly pay the invoice.</h4>
                            <a href="{{ url('billing') }}" class="btn btn-primary btn-sm">Pay Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @elseif ($company_item->status == 'Active')
        <div class="card-box">
            <h3 class="m-0">Welcome {{ $company_owner->company->company_name }}</h3>
            <div id="pie_chart" style="width: 100%; height: 400px; margin: 0 auto"></div>
        </div>
        @endif


        <?php /* Chart Statisticks [Start] */ ?>


        <?php /* Chart Statisticks [End] */ ?>


        <?php /* Recent Leads [Start] */ ?>
        <div class="card-box">
            <h4 class="header-title mb-4">Recent Leads</h4>

            <div class="table-responsive">
                <table class="table table-hover table-centered m-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User Name</th>
                            <th>Phone</th>
                            <th>Location</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>
                                <span class="avatar-sm-box bg-success">L</span>
                            </th>
                            <td>
                                <h5 class="m-0 font-15">Louis Hansen</h5>
                                <p class="m-0 text-muted"><small>Web designer</small></p>
                            </td>
                            <td>+12 3456 789</td>
                            <td>USA</td>
                            <td>07/08/2016</td>
                        </tr>

                        <tr>
                            <th>
                                <span class="avatar-sm-box bg-primary">C</span>
                            </th>
                            <td>
                                <h5 class="m-0 font-15">Craig Hause</h5>
                                <p class="m-0 text-muted"><small>Programmer</small></p>
                            </td>
                            <td>+89 345 6789</td>
                            <td>Canada</td>
                            <td>29/07/2016</td>
                        </tr>

                        <tr>
                            <th>
                                <span class="avatar-sm-box bg-brown">E</span>
                            </th>
                            <td>
                                <h5 class="m-0 font-15">Edward Grimes</h5>
                                <p class="m-0 text-muted"><small>Founder</small></p>
                            </td>
                            <td>+12 29856 256</td>
                            <td>Brazil</td>
                            <td>22/07/2016</td>
                        </tr>

                        <tr>
                            <th>
                                <span class="avatar-sm-box bg-pink">B</span>
                            </th>
                            <td>
                                <h5 class="m-0 font-15">Bret Weaver</h5>
                                <p class="m-0 text-muted"><small>Web designer</small></p>
                            </td>
                            <td>+00 567 890</td>
                            <td>USA</td>
                            <td>20/07/2016</td>
                        </tr>

                        <tr>
                            <th>
                                <span class="avatar-sm-box bg-orange">M</span>
                            </th>
                            <td>
                                <h5 class="m-0 font-15">Mark</h5>
                                <p class="m-0 text-muted"><small>Web design</small></p>
                            </td>
                            <td>+91 123 456</td>
                            <td>India</td>
                            <td>07/07/2016</td>
                        </tr>

                    </tbody>
                </table>

            </div>
            <!-- table-responsive -->
        </div>
        <?php /* Recent Leads [End] */ ?>

        @if (isset($news) && count($news) > 0)
        <div class="card-box">
            <h4 class="header-title mb-4">Latest News</h4>

            @foreach ($news AS $news_item)
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <div class="card-widgets">
                        <i class="far fa-calendar-alt"></i> {{ $news_item->date }}
                    </div>

                    <h3 class="card-title text-white mb-0">
                        <i class="far fa-newspaper"></i> {{ $news_item->title }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="text-left">
                        <div class="short_content">
                            <p>
                                {!! substr($news_item->short_content, 0, 200) !!}
                                <a href="javascript:;" class="read_full_content">Read More</a>
                            </p>
                        </div>

                        <div class="full_content" style="display: none;">
                            {!! $news_item->content!!}
                            <a href="javascript:;" class="read_less_content">Read Less</a>
                        </div>

                        <?php /* <div class="clearfix">&nbsp;</div>

                        <div class="text-left">
                            <a href="" class="badge badge-primary"><i class="far fa-comments"></i>
                                Comments</a>
                        </div> */ ?>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
        @endif

    </div>

    @include('company.profile._company_profile_sidebar')
</div>
@endsection

@section ('page_js')
@include('company.profile._js')

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {packages: ['corechart']});
    function drawChart() {
        // Define the chart to be drawn.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Browser');
        data.addColumn('number', 'Percentage');
        data.addRows([
            ['Category 1', 600],
            ['Category 2', 490],
            ['Category 3', 250],
            ['Category 4', 410],
        ]);

        // Set chart options
        var options = {
            'title':'Leads Summary Category Wise',
            is3D:true
        };

        // Instantiate and draw the chart.
        var chart = new google.visualization.PieChart(document.getElementById('pie_chart'));
        chart.draw(data, options);
    }
    google.charts.setOnLoadCallback(drawChart);
</script>


<script type="text/javascript">
    $(function (){
        $(".read_full_content").on("click", function (){
            $(this).parents(".text-left").find(".short_content").hide();
            $(this).parents(".text-left").find(".full_content").show();
        });

        $(".read_less_content").on("click", function (){
            $(this).parents(".text-left").find(".short_content").show();
            $(this).parents(".text-left").find(".full_content").hide();
        });

        $("#close_btn").on("click", function (){
            $(this).parents("#welcome_block").remove();
        });
    });
</script>
@endsection
