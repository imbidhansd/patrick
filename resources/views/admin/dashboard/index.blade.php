@extends('admin.layout')

@section ('content')
@include('admin.includes.breadcrumb', ['breadCrumbArray' => ['Dashboard' => '']])

@include('flash::message')
<div class="row text-center">
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
        <a href="{{ url('admin/leads?search[leads.state_id]=&from_date='.now()->format(env('DATE_FORMAT')).'&to_date='.now()->format(env('DATE_FORMAT'))) }}">
            <div class="card-box widget-box-one">
                <div class="wigdet-one-content">
                    <p class="m-0 text-uppercase text-overflow">Today Leads</p>
                    <h2 class="text-danger"><span data-plugin="counterup">{{ $todays_leads }}</span></h2>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
        @php
            $last_seven_date = \Carbon\Carbon::today()->subDays(7);
            $last_thirty_date = \Carbon\Carbon::today()->subDays(30);
        @endphp
        <a href="{{ url('admin/leads?search[leads.state_id]=&from_date='.$last_seven_date->format(env('DATE_FORMAT')).'&to_date='.now()->format(env('DATE_FORMAT'))) }}">
            <div class="card-box widget-box-one">
                <div class="wigdet-one-content">
                    <p class="m-0 text-uppercase text-overflow">Last 7 days Leads</p>
                    <h2 class="text-dark"><span data-plugin="counterup">{{ $seven_days_leads }}</span></h2>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
        <a href="{{ url('admin/leads?search[leads.state_id]=&from_date='.$last_thirty_date->format(env('DATE_FORMAT')).'&to_date='.now()->format(env('DATE_FORMAT'))) }}">
            <div class="card-box widget-box-one">
                <div class="wigdet-one-content">
                    <p class="m-0 text-uppercase text-overflow">Last 30 days Leads</p>
                    <h2 class="text-warning"><span data-plugin="counterup">{{ $thirty_days_leads }}</span></h2>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
        <a href="{{ url('admin/leads') }}">
            <div class="card-box widget-box-one">
                <div class="wigdet-one-content">
                    <p class="m-0 text-uppercase text-overflow">All Time</p>
                    <h2 class="text-success"><span data-plugin="counterup">{{ $total_leads }}</span></h2>
                </div>
            </div>
        </a>
    </div>
    <!-- end col -->

</div>


<div class="card-box">
    <div class="row">
        <div class="col-md-6">
            <h5 class="header-title">Companies By Level</h5>
            
            @if (isset($top_menu_membership_levels) && count($top_menu_membership_levels) > 0)
            <ul class="list-group bs-ui-list-group">
                @foreach ($top_menu_membership_levels as $membership_level_item)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ route('company_by_membership_level', ['membership_level' => $membership_level_item->slug]) }}">{{ $membership_level_item->title }}</a>
                    <span class="badge badge-{{ $membership_level_item->color }} badge-pill">
                        <a href="{{ route('company_by_membership_level', ['membership_level' => $membership_level_item->slug]) }}" class="text-white">
                            {{  $membership_level_item->companies_count }}
                        </a>
                    </span>
                </li>
                @endforeach
            </ul>
            @endif
        </div>
        <!-- end col -->

        <div class="col-md-6 mt-4 mt-lg-0">
            <h5 class="header-title">Statistics of company signup</h5>
            <div class="list-group">
                <a href="#" class="list-group-item list-group-item-action">
                    Today
                    <span class="badge badge-primary badge-brown badge-pill float-right">{{ $todays_companies}}</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    Last 7 Days
                    <span class="badge badge-dark badge-pill float-right">{{ $seven_days_companies }}</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    Last 30 Days
                    <span class="badge badge-info badge-pill float-right">{{ $thirty_days_companies }}</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    All Time
                    <span class="badge badge-success badge-pill float-right">{{ $total_companies }}</span>
                </a>
            </div>
            <!-- list-group -->
        </div>
        <!-- end col -->
    </div>
</div>

<?php /* Recent Leads [Start] */ ?>
<div class="clearfix"></div>
<div class="row">
    @if (isset($recent_companies) && count($recent_companies) > 0)
    <div class="col-xl-12">
        <div class="card-box">
            <h4 class="header-title mb-4">Recent Companies</h4>

            <div class="table-responsive">
                <table class="table table-hover table-centered m-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Company Name</th>
                            <th>Phone</th>
                            <th>Zipcode</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recent_companies AS $company_item)
                        <tr>
                            <th>
                                @if (!is_null($company_item->company_logo))
                                <a href="{{ url('admin/companies/'.$company_item->id.'/edit') }}" class="text-primary">
                                    <img src="{{ asset('uploads/media/fit_thumbs/100x100/'.$company_item->company_logo->file_name) }}" alt="user" class="avatar-sm rounded-circle">
                                </a>
                                @endif
                            </th>
                            <td>
                                <h5 class="m-0 font-15">
                                    <a href="{{ url('admin/companies/'.$company_item->id.'/edit') }}" class="text-primary">{{ $company_item->company_name }}</a>
                                </h5>
                                <p class="m-0 text-muted">
                                    <small>{{ $company_item->membership_level->title }}</small>
                                </p>
                            </td>
                            <td>{{ $company_item->main_company_telephone }}</td>
                            <td>{{ $company_item->main_zipcode }}</td>
                            <td>
                                <span class="badge badge-{{ $company_item->color }}">{{ ucfirst($company_item->status) }}</span>
                            </td>
                            <td>{{ $company_item->created_at->format(env('DATE_FORMAT')) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            <!-- table-responsive -->
        </div>
        <!-- end card -->
    </div>
    @endif
    <!-- end col -->


    @if (isset($recent_leads) && count($recent_leads) > 0)
    <div class="col-xl-12">
        <div class="card-box">
            <h4 class="header-title mb-4">Recent Leads</h4>

            <div class="table-responsive">
                <table class="table table-hover table-centered m-0">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Service Category</th>
                            <th>Phone</th>
                            <th>Zipcode</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recent_leads AS $lead_item)
                        <tr>
                            <td>
                                <h5 class="m-0 font-15">{{ $lead_item->full_name }}</h5>
                                <p class="m-0 text-muted">
                                    <small>{{ $lead_item->email }}</small>
                                </p>
                            </td>
                            <td>
                                <h5 class="m-0 font-15">{{ $lead_item->service_category->title }}</h5>
                                <p class="m-0 text-muted">
                                    <small>{{ $lead_item->main_category->title }}</small>
                                </p>
                            </td>
                            <td>{{ $lead_item->phone }}</td>
                            <td>{{ $lead_item->zipcode }}</td>
                            <td>{{ $lead_item->created_at->format(env('DATE_FORMAT')) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- table-responsive -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
    @endif
</div>
<?php /* Recent Leads [End] */ ?>



<?php /* Recent Leads [Start] */ ?>
<div class="row">
    @if (isset($top_thirty_days_service_category) && count($top_thirty_days_service_category) > 0)
    <div class="col-xl-6">
        <div class="card-box">
            <h4 class="header-title mb-4">Last 30 Days Top Service Category</h4>

            <div class="table-responsive">
                <table class="table table-hover table-centered m-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Service Category</th>
                            <th>Leads</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($top_thirty_days_service_category AS $i => $service_category_item)
                        <tr>
                            <th>{{ $i+1 }}</th>
                            <td>
                                <h5 class="m-0 font-15">{{ $service_category_item->service_category->title }}</h5>
                                <p class="m-0 text-muted">
                                    <small>{{ $service_category_item->main_category->title }}</small>
                                </p>
                            </td>
                            <td>{{ $service_category_item->total_leads }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- table-responsive -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
    @endif


    @if (isset($top_six_month_service_category) && count($top_six_month_service_category) > 0)
    <div class="col-xl-6">
        <div class="card-box">
            <h4 class="header-title mb-4">Last 6 Months Top Service Category</h4>

            <div class="table-responsive">
                <table class="table table-hover table-centered m-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Service Category</th>
                            <th>Leads</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($top_six_month_service_category AS $i => $service_category_item)
                        <tr>
                            <th>{{ $i+1 }}</th>
                            <td>
                                <h5 class="m-0 font-15">{{ $service_category_item->service_category->title }}</h5>
                                <p class="m-0 text-muted">
                                    <small>{{ $service_category_item->main_category->title }}</small>
                                </p>
                            </td>
                            <td>{{ $service_category_item->total_leads }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- table-responsive -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
    @endif
</div>
<?php /* Recent Leads [End] */ ?>



@endsection
