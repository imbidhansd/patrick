<div class="col-lg-3 col-md-4 col-sm-12">
    <div class="card b-0">
        <div class="card-header bg-header">
            <h3 class="card-title text-white mb-0">Company Level & Status Information</h3>
        </div>
        <div class="card-body p-0">
            <div class="text-center">
                <h6>Welcome <span class="text-primary">{{ $company_item->company_name }}</span></h6>
            </div>
            <div class="text-left">
                <table class="mb-3" width="100%" cellpadding="8">
                    <tr>
                        <td colspan="2" class="text-center">Level: &nbsp; {{ $company_item->membership_level->title }}</td>
                    </tr>
                    @if ($company_item->membership_level->paid_members == 'yes')

                    @php
                    $membership_status = \App\Models\MembershipStatus::where('title', $company_item->status)->active()->first();
                    @endphp
                    <tr>
                        <td class="text-right" width="45%">Status:</td>
                        <td>
                            <span class="badge badge-{{ $membership_status->color }} border-radius-0">{{ $company_item->status }}</span>
                        </td>
                    </tr>

                    @if ($company_item->status == 'Unpaid Invoice')
                    <tr>
                        <td class="text-right" width="45%"></td>
                        <td>
                            <a class="btn btn-sm btn-danger" href="{{ url('billing') }}">Pay Invoice</a>
                        </td>
                    </tr>
                    @endif


                    @endif
                    
                    
                    @if ($company_item->status == 'Approved')
                    <tr>
                        <td class="text-right"></td>
                        <td class="pt-0">
                            <a href="javascript:;" class="badge badge-primary border-radius-0" data-toggle="modal" data-target="#payNowModal">Activate Listing &nbsp;<i class="fas fa-play-circle"></i></a>
                        </td>
                    </tr>
                    @endif

                    @if ($company_item->status == 'Active')
                        <tr>
                            <td class="text-right">Member Since:</td>
                            <td>
                                @if ($company_item->approval_date != '')
                                {{ $company_item->approval_date }}
                                @else
                                {{ $company_item->created_at->format(env('DATE_FORMAT')) }}
                                @endif
                            </td>
                        </tr>
                        
                        @if (!is_null($company_item->renewal_date))
                        <tr>
                            <td class="text-right">Renewal Date:</td>
                            <td>{{ $company_item->renewal_date }}</td>
                        </tr>
                        @endif
                        
                        <tr>
                            <td class="text-right">Leads Status:</td>
                            <td class="p-0">
                                <span class="badge {{ (($company_item->leads_status == 'active') ? 'badge-success' : 'badge-danger') }} border-radius-0">{{ (($company_item->leads_status == 'active') ? ucfirst($company_item->leads_status) : 'Paused') }}</span>
                            </td>
                        </tr>
                        
                        <tr>
                            <td></td>
                            <td class="p-0">
                                @if ($company_item->leads_status == 'active')
                                <a href="javascript:;" data-type="Pause"
                                   class="badge badge-danger border-radius-0" data-toggle="modal" data-target="#leadStatusUpdateModel">Pause Leads &nbsp;<i class="fas fa-pause-circle"></i></a>
                                @else
                                <a href="javascript:;" data-type="Reactive Listing" class="badge badge-primary border-radius-0 change_lead_status">Unpause Leads &nbsp;<i class="fas fa-play-circle"></i></a>
                                @endif
                            </td>
                        </tr>
                        
                        @if ($company_item->leads_status == 'inactive' && !is_null($company_item->lead_resume_date))
                        <tr>
                            <td class="text-right">Leads Resume Date:</td>
                            <td>
                                <span>{{ \App\Models\Custom::date_formats($company_item->lead_resume_date, env('DB_DATE_FORMAT'), env('DATE_FORMAT')) }}</span>
                            </td>
                        </tr>
                        @endif
                    @endif

                    @if ($company_item->status == 'Subscribed' || $company_item->status == 'Unsubscribed' || $company_item->status == 'Registered')
                    <tr>
                        <td class="text-right">Status:</td>
                        <td class="p-0">
                            @if ($company_item->status == 'Subscribed')
                            <span class="badge badge-success subscription_bar">{{ ucfirst($company_item->status) }} &nbsp;<i class="fas fa-play-circle"></i></span>
                            @else
                            <span class="badge badge-danger subscription_bar">{{ ucfirst($company_item->status) }} &nbsp;<i class="fas fa-pause-circle"></i></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="p-0">
                            @if ($company_item->status == 'Subscribed')
                            <a href="javascript:;" data-type="unsubscribe" class="badge badge-danger change_subscription subscription_bar">Unsubscribe &nbsp;<i class="fas fa-pause-circle"></i></a>
                            @else
                            <a href="javascript:;" data-type="subscribe" class="badge badge-primary change_subscription subscription_bar">Resubscribe &nbsp;<i class="fas fa-play-circle"></i></a>
                            @endif

                        </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        @if ($company_item->membership_level->paid_members == 'no')
        <div class="card-footer text-center b-0 bc-none pt-5">
            <?php /* @if ($company_item->membership_level_id != 7) */ ?>
            <p class="mb-2">
                <a href="{{ url('referral-list/full-listing-more') }}" class="text-primary">Explore Upgrade Options</a>
            </p>
            
            <?php /* @endif */ ?>

            <p class="mb-0">
                Package Code? <a href="javascript:;" class="promocode_link" data-toggle="modal" data-target="#updatePromocodeModal">Click Here</a>
            </p>
            <p class="mt-3">
                @php 
                    //check package is created
                    $check_package = \App\Models\Package::where('company_id', $company_item->id)->whereNotNull('package_code')->active()->latest()->first();
                @endphp
                
                @if (!is_null($check_package))
                <a href="javascript:;" data-toggle="modal" data-target="#updatePromocodeModal" class="btn btn-sm btn_upgrade promocode_link">Upgrade Now</a>
                @else
                <a href="{{ url('referral-list/application-process') }}" class="btn btn-sm btn_upgrade">Upgrade Now</a>
                @endif
                
                
                
            </p>
        </div>
        @elseif ($company_item->status == 'Paid Pending')
        <div class="card-footer text-center b-0 bc-none pt-4">
            <p class="text-danger">You have not completed your application. Please click here to complete your application.</p>
            <a href="{{ url('account/application') }}" class="btn btn-sm btn-danger">Submit Your Application</a>
        </div>
        @endif
    </div>

    @if ($company_item->membership_level->paid_members == 'yes' && ($company_item->status == 'Pending Approval' || $company_item->status == 'Final Review'))
    @include ('company.profile.approval_status._company_approval_status')
    @endif


    @if ($company_item->status != 'Paid Pending')
    <div class="card b-0">
        <div class="card-header bg-header">
            <h3 class="card-title text-white mb-0">Company Profile Page</h3>
        </div>
        <div class="card-body p-0">

            <ul class="list-group bs-ui-list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center b-0">
                    Company Page Views Current Month
                    <span class="badge badge-info badge-pill ml-2">{{ $cur_month_profile_views }}</span>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center b-0">
                    Company Page Views Last Month
                    <span class="badge badge-info badge-pill ml-2">{{ $last_month_profile_views }}</span>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center b-0">
                    Company Page Views All Time
                    <span class="badge badge-info badge-pill ml-2">{{ $all_profile_views }}</span>
                </li>

                @if (!is_null($company_item->company_page_media))
                <li class="list-group-item text-center b-0">
                    <a href="{{ url('/', ['company_slug' => $company_item->slug]) }}" class="company_page_image" target="_blank">
                        <img src="{{ asset('/uploads/company_page/'.$company_item->company_page_media->file_name) }}" class="img-responsive" width="150" />
                    </a>
                </li>
                @endif
            </ul>
        </div>
        <div class="card-footer text-center b-0 bc-none">
            <a href="{{ url('/', ['company_slug' => $company_item->slug]) }}" target="_blank" class="btn btn-sm btn-info">View Company Page</a>
        </div>
    </div>

    <div class="card b-0">
        <div class="card-header bg-header">
            <h3 class="card-title text-white mb-0">Leads</h3>
        </div>
        <div class="card-body p-0">

            <ul class="list-group bs-ui-list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center b-0">
                    New Leads This Month
                    <span class="badge badge-info badge-pill ml-2">{{ $new_this_month_leads }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center b-0">
                    Find A Pro Leads
                    <span class="badge badge-info badge-pill ml-2">{{ $find_a_pro_leads }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center b-0">
                    Company Page Leads
                    <span class="badge badge-info badge-pill ml-2">{{ $company_page_leads }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center b-0">
                    Total Number of Leads To Date
                    <span class="badge badge-info badge-pill ml-2">{{ $total_leads }}</span>
                </li>
            </ul>

        </div>
        <div class="card-footer text-center b-0 bc-none">
            <a href="{{ url('leads-archive-inbox') }}" class="btn btn-sm btn-info">View All Leads
            </a>
        </div>
    </div>

    <div class="card b-0">
        <div class="card-header bg-header">
            <h3 class="card-title text-white mb-0">Feedback</h3>
        </div>
        <div class="card-body p-0">

            <ul class="list-group bs-ui-list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center b-0">
                    Feedback
                    <span class="badge badge-info badge-pill ml-2">{{ \App\Models\Feedback::where('company_id', $company_item->id)->count('id') }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center b-0">
                    Complaints
                    <span class="badge badge-info badge-pill ml-2">{{ \App\Models\Complaint::where('company_id', $company_item->id)->count('id') }}</span>
                </li>
            </ul>
        </div>
        <div class="card-footer text-center b-0 bc-none">
            <a href="{{ url('feedback') }}" class="btn btn-sm btn-info">Read All Feedback</a>
        </div>
    </div>
    @endif

    @if (!is_null($membership_video) && !is_null($membership_video->video_id) && Session::get('video_dismissed') == 'yes')
    <div class="card b-0">
        <div class="card-body p-0">
            <a href="javascript:;" class="btn-sm p-0 text-dark float-right dismiss_video" title="Dismiss from Sidebar"><i class="fas fa-window-close"></i></a>
            <div class="embed-container embed-container-aspect-ratio">
                <iframe src="https://player.vimeo.com/video/{{ $membership_video->video_id }}" width="100%" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
            </div>
        </div>
    </div>
    @endif
</div>


{!! Form::open(['url' => url('update-company-subscription'), 'id' => 'udpate_company_subscription_form']) !!}
{!! Form::hidden('sub_type', null, ['id' => 'sub_type']) !!}
{!! Form::close() !!}

{!! Form::open(['url' => url('update-company-lead-status'), 'id' => 'udpate_company_lead_status_form']) !!}
{!! Form::hidden('lead_status', null, ['id' => 'lead_status']) !!}
{!! Form::close() !!}


<div class="modal fade" id="updatePromocodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Have Promotional Code? Enter below.</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('account/upgrade/promotional-code'), 'class' => 'module_form']) !!}
            <div class="modal-body">
                <div class="form-group">
                    <label>Promotional Code</label>
                    {!! Form::text('promocode', null, ['class' => 'form-control', 'required' => true, 'id' => 'promocode', 'placeholder' => 'Enter Promotional Code', 'maxlength' => 8, 'data-parsley-maxlength' => 8]) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light">Apply</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>



<div id="leadStatusUpdateModel" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white mt-0">Pause Leads</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['url' => url('update-company-lead-status'), 'class' => 'module_form', 'id' => 'lead_status_update_form']) !!}

            {!! Form::hidden('lead_status', 'Pause') !!}
            <div class="modal-body">
                @php
                $membershipLevelObj = $company_item->membership_level;
                @endphp

                @if (!is_null($membershipLevelObj->pause_lead_message))
                <div class="card widget-box-three mt-3">
                    <div class="card-body">
                        <div class="float-left mt-2 mr-3">
                            <i class="fas fa-exclamation-triangle display-4 m-0 text-danger"></i>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-danger font-14">
                                {!! $membershipLevelObj->pause_lead_message !!}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="card card-border card-danger">
                    <div class="card-header border-danger bg-transparent">Please choose your options:</div>
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <label>When would you like to pause leads?</label>

                            <div class="radio radio-primary">
                                {!! Form::radio('lead_pause_option', 'today', null, ['class' => 'lead_pause_option', 'id' => 'lead_pause_option_today', 'required' => true]) !!}
                                <label for="lead_pause_option_today">Immediately</label>
                            </div>

                            <div class="radio radio-primary">
                                {!! Form::radio('lead_pause_option', 'custom', null, ['class' => 'lead_pause_option', 'id' => 'lead_pause_option_custom', 'required' => true]) !!}
                                <label for="lead_pause_option_custom">Custom Date</label>
                            </div>
                        </div>


                        <div id="lead_pause_date_div" style="display: none;">
                            <div class="form-group mb-0">
                                {!! Form::label('Lead Pause Date') !!}
                                <div class="input-group">
                                    {!! Form::text('lead_pause_date', null, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'DD/MM/YYYY', 'id' => 'lead_pause_date' , 'required' => false]) !!}
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-primary text-white b-0"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card card-border card-info">
                    <div class="card-header border-info bg-transparent">Please choose when you would like to resume leads?</div>
                    <div class="card-body">

                        <div class="form-group mb-0">
                            <label>On what date would you like to resume leads?</label>

                            <div class="radio radio-primary">
                                {!! Form::radio('lead_resume_option', 'next_month', null, ['class' => 'lead_resume_option', 'id' => 'lead_resume_option_next_month', 'required' => true]) !!}
                                <label for="lead_resume_option_next_month">On the 1st Day of the next calendar month</label>
                            </div>

                            <div class="radio radio-primary">
                                {!! Form::radio('lead_resume_option', 'custom', null, ['class' => 'lead_resume_option', 'id' => 'lead_resume_option_custom', 'required' => true]) !!}
                                <label for="lead_resume_option_custom">Custom Date</label>
                            </div>
                        </div>


                        <div id="lead_resume_date_div" style="display: none;">
                            <div class="form-group mb-0">
                                {!! Form::label('Lead Resume Date') !!}
                                <div class="input-group">
                                    {!! Form::text('lead_resume_date', null, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'DD/MM/YYYY', 'id' => 'lead_resume_date' , 'required' => false]) !!}
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-primary text-white b-0"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect lead_status_update_btn">Submit</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->




<div class="modal fade" id="payNowModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Ready to activate your listing?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <p>Please pay the invoice amount due upon approval to activate your listing.</p>
                <div class="clearfix">&nbsp;</div>
                <a href="{{ url('billing') }}" class="btn btn-primary btn-sm">Review Invoice</a>
            </div>
        </div>
    </div>
</div>