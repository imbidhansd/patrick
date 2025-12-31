@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')


<div class="row">
    <div class="col-lg-9 col-md-8 col-sm-12">
        @if ($company_item->membership_level->charge_type == 'ppl_price')

        @php
        $card_class = 'col-xl-4 col-lg-4 col-sm-4';
        if ($company_item->temporary_budget != $company_item->permanent_budget):
        $card_class = 'col-xl-3 col-lg-3 col-sm-3';
        endif;
        @endphp


        <div id="welcome_block">
            <div class="row">
                <div class="{{ $card_class }}">
                    <div class="card-box widget-box-one">
                        <div class="wigdet-one-content text-center">
                            <h5 class="m-0 text-uppercase text-overflow">Monthly Budget</h5>
                            <h2 class="text-info">$<span data-plugin="counterup">{{ number_format($company_item->temporary_budget, 2) }}</span></h2>
                            <p class="text-center mb-0"><a href="javascript:;" class="btn btn-xs btn-dark" data-toggle="modal" data-target="#monthlyBudgetModel"><i class="fa fa-edit"></i> Change Monthly Budget</a></p>
                        </div>
                    </div>
                </div>  

                @if ($company_item->temporary_budget != $company_item->permanent_budget)
                <div class="{{ $card_class }}">
                    <div class="card-box widget-box-one">
                        <div class="wigdet-one-content text-center">
                            <h5 class="m-0 text-uppercase text-overflow">Next Month Budget</h5>
                            <h2 class="text-info">$<span data-plugin="counterup">{{ number_format($company_item->permanent_budget, 2) }}</span></h2>
                        </div>
                    </div>
                </div>  
                @endif

                <div class="{{ $card_class }}">
                    <div class="card-box widget-box-one">
                        <div class="wigdet-one-content text-center">
                            <h5 class="m-0 text-uppercase text-overflow"><span class="text-danger">Used Budget</span><br/>in this month</h5>
                            <h2 class="text-danger">$<span data-plugin="counterup">{{ number_format($current_used_budget, 2) }}</span></h2>
                        </div>
                    </div>
                </div>  
                <div class="{{ $card_class }}">
                    <div class="card-box widget-box-one">
                        <div class="wigdet-one-content text-center">
                            <h5 class="m-0 text-uppercase text-overflow"><span class="text-success">Remaining Budget</span><br/>in this month</h5>
                            <h2 class="text-success">$<span data-plugin="counterup">{{ number_format($remaining_budget, 2) }}</span></h2>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h3 class="card-title text-white mb-0">Leads</h3>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-sm-4">
                        <div class="card-box widget-box-one">
                            <div class="wigdet-one-content text-center">
                                <h5 class="m-0 text-uppercase text-overflow"><span class="text-info">All</span></h5>
                                <h2 class="text-info">
                                    <span data-plugin="counterup">
                                        {{ $all_leads }}
                                    </span>
                                </h2>
                            </div>
                        </div>
                    </div>  
                    <div class="col-xl-4 col-lg-4 col-sm-4">
                        <div class="card-box widget-box-one">
                            <div class="wigdet-one-content text-center">
                                <h5 class="m-0 text-uppercase text-overflow"><span class="text-success">Read</span></h5>
                                <h2 class="text-success">
                                    <span data-plugin="counterup">
                                        {{ $read_leads }}
                                    </span>
                                </h2>
                            </div>
                        </div>
                    </div>  
                    <div class="col-xl-4 col-lg-4 col-sm-4">
                        <div class="card-box widget-box-one">
                            <div class="wigdet-one-content text-center">
                                <h5 class="m-0 text-uppercase text-overflow"><span class="text-danger">Unread</span></h5>
                                <h2 class="text-danger">
                                    <span data-plugin="counterup">
                                        {{ $unread_leads }}
                                    </span>
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>

                @if (isset($leads) && count($leads) > 0)
                <a href="javascript:;" class="btn btn-warning search_lead_btn float-right">Search Leads</a>
                <div class="clearfix">&nbsp;</div>
                <div class="clearfix">&nbsp;</div>
                
                <div class="row search_lead_form {{ $search_form_open ? 'open' : '' }}">
                    <div class="col-xl-12 col-lg-12 col-sm-12">
                        {!! Form::open(['method' => 'GET']) !!}

                        <div class="row">
                            @if ($company_item->membership_level_id != 1)

                            @if (isset($company_service_categories) && count($company_service_categories) > 0)
                            @php
                            $main_category_id = "";
                            @endphp
                            <div class="col-xl-6 col-lg-6 col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('Search By Service Category') !!}
                                    <select name="service_category" class="form-control custom-select">
                                        <option value="">Select Service Category</option>
                                        @foreach ($company_service_categories AS $service_category_item)

                                        @if ($main_category_id != $service_category_item->main_category_id)

                                        @if (!$loop->first)
                                        </optgroup>
                                        @endif
                                        <optgroup label="{{ $service_category_item->main_category->title }}">
                                            {{ $service_category_item->main_category->title }}
                                            @php
                                            $main_category_id = $service_category_item->main_category_id;
                                            @endphp
                                            @endif

                                            @php $selected = ""; @endphp
                                            @if (Request::has('service_category') && Request::get('service_category') == $service_category_item->service_category_id)
                                            @php $selected = "selected"; @endphp
                                            @endif

                                            <option value="{{ $service_category_item->service_category_id }}" {{ $selected }}>{{ $service_category_item->service_category->title }}</option>

                                            @if ($loop->last)
                                        </optgroup>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif

                            <div class="col-xl-3 col-lg-3 col-sm-3">
                                <div class="form-group">
                                    {!! Form::label('Search By') !!}
                                    {!! Form::text('search_by', Request::has('search_by') ? Request::get('search_by') : null, ['class' => 'form-control', 'placeholder' => 'Name/Email/Phone']) !!}
                                </div>
                            </div>
                            @endif

                            <div class="col-xl-3 col-lg-3 col-sm-3">
                                <div class="form-group">
                                    {!! Form::label('Search By') !!}
                                    {!! Form::text('zipcode', Request::has('zipcode') ? Request::get('zipcode') : null, ['class' => 'form-control', 'placeholder' => 'Zipcode']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-3 col-lg-3 col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('Start Date') !!}
                                    <div class="input-group">
                                        {!! Form::text('from_date', Request::has('from_date') ? Request::get('from_date') : null, ['class' => 'form-control date_field', 'placeholder' => 'DD/MM/YYYY', 'autocomplete' => 'off']) !!}
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-primary text-white b-0"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('End Date') !!}
                                    <div class="input-group">
                                        {!! Form::text('to_date', Request::has('to_date') ? Request::get('to_date') : null, ['class' => 'form-control date_field', 'placeholder' => 'DD/MM/YYYY', 'autocomplete' => 'off']) !!}
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-primary text-white b-0"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-3 col-sm-6">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="clearfix"></div>
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <a href="{{ url('leads-archive-inbox') }}" class="btn btn-dark">Reset</a>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}

                        <div class="clearfix">&nbsp;</div>
                    </div>
                </div>

                
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Service Requested</th>
                                <th>Date Requested</th>
                                <th>Timeframe</th>

                                @if ($company_item->membership_level->charge_type == 'ppl_price')
                                <th>Cost</th>
                                @endif
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leads AS $lead_item)

                            @php $pplTrCls = ""; @endphp
                            @if ($company_item->membership_level->charge_type == 'ppl_price' && !is_null($lead_item->lead->dispute_status) && $lead_item->lead->dispute_status == 'approved')
                            @php $pplTrCls = "table-success"; @endphp
                            @elseif ($company_item->membership_level->charge_type == 'ppl_price' && !is_null($lead_item->lead->dispute_status) && $lead_item->lead->dispute_status == 'declined')
                            @php $pplTrCls = "table-danger"; @endphp
                            @elseif ($company_item->membership_level->charge_type == 'ppl_price' && !is_null($lead_item->lead->dispute_status) && $lead_item->lead->dispute_status == 'in process')
                            @php $pplTrCls = "table-info"; @endphp
                            @endif

                            @php $readTrCls = ""; @endphp
                            @if ($lead_item->is_checked == 'yes')
                            @php $readTrCls = "table-primary"; @endphp
                            @endif

                            <tr class="{{ $pplTrCls.' '.$readTrCls }}">
                                <td>
                                    @if ($lead_item->is_hidden == 'no')
                                    {{ $lead_item->lead->full_name }}
                                    @else
                                    Hidden
                                    @endif
                                </td>
                                <td>
                                    @if ($lead_item->is_hidden == 'no')
                                    {{ $lead_item->lead->email }}
                                    @else
                                    Hidden
                                    @endif
                                </td>
                                <td>
                                    @if ($lead_item->is_hidden == 'no')
                                    {{ $lead_item->lead->phone }}
                                    @else
                                    Hidden
                                    @endif
                                </td>
                                <td>
                                    {{ $lead_item->lead->service_category->title }}
                                </td>
                                <td>
                                    @if (!is_null($lead_item->lead))
                                    {{ $lead_item->lead->created_at->format(env('DATE_FORMAT')) }}
                                    @endif
                                </td>
                                <td>{{ $lead_item->lead->timeframe }}</td>
                                @if ($company_item->membership_level->charge_type == 'ppl_price')
                                <td>${{ number_format($lead_item->fee, 2) }}</td>
                                @endif
                                <td>
                                    <div class="btn-group btn-group-solid">
                                        <?php /* @if ($lead_item->is_checked == 'no') */ ?>
                                        <a title="View Lead" href="javascript:;" data-id="#detail_tr_{{ $lead_item->lead_id }}" data-is_checked="{{ $lead_item->is_checked }}" data-company_lead_id="{{ $lead_item->id }}" class="btn btn-info btn-xs expand_link"><i class="fas fa-eye"></i></a>
                                        <?php /* @endif */ ?>

                                        <?php /* <a title="Delete Lead" href="{{ url('/leads-archive-inbox/delete-lead', ['company_lead_id' => $lead_item->id]) }}" class="btn btn-danger lead_delete_btn btn-xs" data-company_lead_id="{{ $lead_item->id }}"><i class="fas fa-trash-alt"></i></a> */ ?>
                                    </div>
                                </td>
                            </tr>

                            <?php /* @if ($lead_item->is_hidden == 'no') */ ?>
                            <tr id="detail_tr_{{ $lead_item->lead_id }}" style="display: none;">
                                <td colspan="7">
                                    <table class="table table-striped">
                                        <tr>
                                            <td class="text-right w-25">Customer Name</td>
                                            <th class="text-left">
                                                @if ($lead_item->is_hidden == 'no')
                                                {{ $lead_item->lead->full_name }}
                                                @else
                                                Hidden
                                                @endif
                                            </th>
                                        </tr>
                                        <tr>
                                            <td class="text-right">Email</td>
                                            <th class="text-left">
                                                @if ($lead_item->is_hidden == 'no')
                                                {{ $lead_item->lead->email }}
                                                @else
                                                Hidden
                                                @endif
                                            </th>
                                        </tr>
                                        <tr>
                                            <td class="text-right">Phone</td>
                                            <th class="text-left">
                                                @if ($lead_item->is_hidden == 'no')
                                                {{ $lead_item->lead->phone }}
                                                @else
                                                Hidden
                                                @endif
                                            </th>
                                        </tr>
                                        <tr>
                                            <td class="text-right">Main Category</td>
                                            <th class="text-left">{{ $lead_item->lead->main_category->title }}</th>
                                        </tr>
                                        <tr>
                                            <td class="text-right">Service Category Type</td>
                                            <th>{{ $lead_item->lead->service_category_type->title }}</th>
                                        </tr>
                                        <tr>
                                            <td class="text-right">Service Category</td>
                                            <th class="text-left">{{ $lead_item->lead->service_category->title }}</th>
                                        </tr>
                                        <tr>
                                            <td class="text-right">Timeframe</td>
                                            <th class="text-left">{{ $lead_item->lead->timeframe }}</th>
                                        </tr>

                                        @if (!is_null($lead_item->lead->price))
                                        <tr>
                                            <td class="text-right">Price</td>
                                            <th class="text-left">${{ number_format($lead_item->lead->price, 2) }}</th>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td class="text-right">Address</td>
                                            <th class="text-left">
                                                @if ($lead_item->is_hidden == 'no')
                                                {{ $lead_item->lead->project_address }} {{ $lead_item->lead->city }} {{ $lead_item->lead->state->name }}
                                                @else
                                                Hidden
                                                @endif
                                            </th>
                                        </tr>
                                        <tr>
                                            <td class="text-right">Zipcode</td>
                                            <th class="text-left">{{ $lead_item->lead->zipcode }}</th>
                                        </tr>
                                        <tr>
                                            <td class="text-right">Project Info</td>
                                            <th class="text-left">
                                                @if ($lead_item->is_hidden == 'no')
                                                {!! $lead_item->lead->content !!}
                                                @else
                                                Hidden
                                                @endif
                                            </th>
                                        </tr>

                                        @if ($company_item->membership_level->charge_type == 'ppl_price')
                                        @if (!is_null($lead_item->lead->dispute_status) && $lead_item->lead->company_id == $company_item->id)
                                        <tr>
                                            <td class="text-right">Dispute</td>
                                            <th class="text-left">
                                                @if($lead_item->lead->dispute_status == 'in process')
                                                <span class="badge badge-orange">{{ ucwords($lead_item->lead->dispute_status) }}</span>
                                                &nbsp;
                                                <a href="javascript:;" class="cancel_lead_dispute" data-lead_id="{{ $lead_item->lead->id }}">Cancel this Dispute</a>
                                                @elseif($lead_item->lead->dispute_status == 'approved')
                                                <span class="badge badge-primary">{{ ucwords($lead_item->lead->dispute_status) }}</span>
                                                @elseif($lead_item->lead->dispute_status == 'declined')
                                                <span class="badge badge-danger">{{ ucwords($lead_item->lead->dispute_status) }}</span>
                                                @elseif($lead_item->lead->dispute_status == 'cancelled')
                                                <span class="badge badge-warning">{{ ucwords($lead_item->lead->dispute_status) }}</span>
                                                
                                                @php
                                                $difference = 0;
                                                $lead_date = \Carbon\Carbon::parse($lead_item->lead->lead_active_date);
                                                $today_date = now();
                                                $difference = $today_date->diffInDays($lead_date);
                                                @endphp

                                                @if ($difference <= 7)
                                                <a href="javascript:;" data-toggle="modal" data-target="#disputeModal" class="generate_lead_dispute" data-lead_id="{{ $lead_item->lead_id }}">Re-Generate Dispute</a>
                                                @endif
                                                @endif
                                            </th>
                                        </tr>
                                            @if($lead_item->lead->dispute_status == 'declined')
                                            <tr>
                                                <td class="text-right">Dispute Decline Reason</td>
                                                <th class="text-left">
                                                    {!! $lead_item->lead->dispute_decline_reason !!}
                                                </th>
                                            </tr>
                                            @endif
                                        @elseif (is_null($lead_item->lead->dispute_status))
                                        <tr>
                                            <td class="text-right">Dispute</td>
                                            <th class="text-left">
                                                @php
                                                $difference = 0;
                                                $lead_date = \Carbon\Carbon::parse($lead_item->lead->lead_active_date);
                                                $today_date = now();
                                                $difference = $today_date->diffInDays($lead_date);
                                                @endphp

                                                @if ($difference <= 10)
                                                <a href="javascript:;" data-toggle="modal" data-target="#disputeModal" class="generate_lead_dispute" data-lead_id="{{ $lead_item->lead_id }}">Generate Dispute</a>
                                                @endif
                                            </th>
                                        </tr>
                                        @endif
                                        @endif
                                    </table>
                                </td>
                            </tr>
                            <?php /* @endif */ ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="clearfix">&nbsp;</div>
                <div class="float-left">
                    {{ $leads->appends($search_list_params)->render() }}
                </div>
                @else
                <div class="clearfix">&nbsp;</div>
                <h4 class="text-danger text-center">
                    No leads here yet!<br/>
                    @if ($company_item->membership_level_id == 1)
                    <br/>Once you begin receiving trial leads, they'll be placed here.<br/>Check back often!
                    @endif
                </h4>
                <div class="clearfix">&nbsp;</div>
                @endif

            </div>
        </div>
    </div>

    @include('company.profile._company_profile_sidebar')
</div>


<div id="monthlyBudgetModel" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white mt-0">Change Monthly Budget</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['url' => url('update-monthly-budget'), 'class' => 'module_form monthly_budget_upgrade_form', ]) !!}
            <div class="modal-body">
                <div class="form-group mb-0">
                    <label>Is this a permanent change or a temporary change?</label>

                    <div class="radio radio-primary">
                        {!! Form::radio('monthly_budget_type', 'Permanent', null, ['id' => 'parmanent_budget', 'class' => 'monthly_budget_type', 'data-parsley-errors-container' => '#monthly_budget_type_error', 'required' => true]) !!}
                        <label for="parmanent_budget">Permanent <span class="text-info">(It will effect from current month and next months too)</span></label>
                    </div>

                    <div class="radio radio-primary">
                        {!! Form::radio('monthly_budget_type', 'Temporary', null, ['id' => 'temporary_budget', 'class' => 'monthly_budget_type', 'data-parsley-errors-container' => '#monthly_budget_type_error', 'required' => true]) !!}
                        <label for="temporary_budget">Temporary <span class="text-danger">(Only for current month)</span></label>
                    </div>

                    <div id="monthly_budget_type_error"></div>
                </div>

                <div id="monthly_budget_effect" style="display: none;">
                    <div class="form-group mt-5">
                        <label>Do you want this change to take effect immediately?</label>

                        <div class="radio radio-primary">
                            {!! Form::radio('monthly_budget_effect', 'Yes', null, ['id' => 'budget_effect_yes', 'class' => 'monthly_budget_effect', 'data-parsley-errors-container' => '#monthly_budget_effect_error', 'required' => false]) !!}
                            <label for="budget_effect_yes">Yes - Immediately</label>
                        </div>

                        <div class="radio radio-primary">
                            {!! Form::radio('monthly_budget_effect', 'No', null, ['id' => 'budget_effect_no', 'class' => 'monthly_budget_effect', 'data-parsley-errors-container' => '#monthly_budget_effect_error', 'required' => false]) !!}
                            <label for="budget_effect_no">No - On the 1st Day of the next calendar month</label>
                        </div>
                    </div>

                    <div id="monthly_budget_effect_error"></div>
                </div>

                <div id="monthly_budget" style="display: none;">
                    <div id="monthly_budget_text" style="display: none;">
                        <p class="text-danger mt-5">
                            Your monthly budget is currently set at
                            <strong>${{ !is_null($company_item->temporary_budget) ? number_format($company_item->temporary_budget, 2) : '0.00' }}</strong>.
                            Please enter an amount higher than <strong>${{ number_format($current_used_budget, 2) }}</strong> which is the amount already spent this month.
                        </p>
                    </div>
                    <div class="form-group mb-0 mt-4">
                        {!! Form::label('Monthly Budget') !!}
                        {!! Form::number('monthly_budget', null, ['class' => 'form-control', 'placeholder' => 'Monthly Budget', 'id' => 'monthly_budget_input', 'step' => '0.01', 'data-parsley-min' => !is_null($company_item->temporary_budget) ? $company_item->temporary_budget : '0.00', 'data-parsley-errors-container' => '#monthly_budget_text_error', 'required' => true]) !!}

                        <div id="monthly_budget_text_error"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect monthly_budget_submit_btn">Submit</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div id="disputeModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white mt-0">Dispute Lead</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['url' => url('leads-archive-inbox/generate-lead-dispute'), 'class' => 'module_form', 'id' => 'lead_dispute_form']) !!}

            {!! Form::hidden('lead_id', null, ['id' => 'lead_id']) !!}

            @php
            $phone_call_time = ['1' => '1 (One)', '2' => '2 (Two)', '3' => '3 (Three)', '4' => '4 (Four)'];
            $email_time = ['1' => '1 (One)', '2' => '2 (Two)', '3' => '3 (Three)'];
            @endphp
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('Reason for disputing') !!}
                    {!! Form::text('dispute_content', null, ['class' => 'form-control', 'placeholder' => 'Reason for disputing', 'required' => true]) !!}
                </div>

                <div class="form-group">
                    <label>Did you call this lead on the telephone?</label>

                    <div class="radio radio-primary">
                        {!! Form::radio('is_phone', 'yes', null, ['id' => 'is_phone_yes', 'class' => 'is_phone', 'data-parsley-errors-container' => '#is_phone_error', 'required' => true]) !!}
                        <label for="is_phone_yes">Yes</label>
                    </div>

                    <div class="radio radio-primary">
                        {!! Form::radio('is_phone', 'no', null, ['id' => 'is_phone_no', 'class' => 'is_phone', 'data-parsley-errors-container' => '#is_phone_error', 'required' => true]) !!}
                        <label for="is_phone_no">No</label>
                    </div>

                    <div id="is_phone_error"></div>
                </div>

                <div id="phone_call_selection" style="display: none;">
                    <div class="form-group">
                        {!! Form::label('How many time?') !!}
                        {!! Form::select('no_of_phone', $phone_call_time, null, ['class' => 'form-control custom-select', 'placeholder' => 'How many time?', 'required' => false]) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label>Did you email this lead?</label>

                    <div class="radio radio-primary">
                        {!! Form::radio('is_email', 'yes', null, ['id' => 'is_email_yes', 'class' => 'is_email', 'data-parsley-errors-container' => '#is_email_error', 'required' => true]) !!}
                        <label for="is_email_yes">Yes</label>
                    </div>

                    <div class="radio radio-primary">
                        {!! Form::radio('is_email', 'no', null, ['id' => 'is_email_no', 'class' => 'is_email', 'data-parsley-errors-container' => '#is_email_error', 'required' => true]) !!}
                        <label for="is_email_no">No</label>
                    </div>

                    <div id="is_email_error"></div>
                </div>

                <div id="email_selection" style="display: none;">
                    <div class="form-group">
                        {!! Form::label('How many time?') !!}
                        {!! Form::select('no_of_email', $email_time, null, ['class' => 'form-control custom-select', 'placeholder' => 'How many time?', 'required' => false]) !!}
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect lead_dispute_submit_btn">Submit</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

{!! Form::open(['id' => 'lead_delete_form']) !!}
{!! Form::hidden('company_lead_id', 0,['id' => 'row_id']) !!}
{!! Form::close() !!}

{!! Form::open(['url' => 'leads-archive-inbox/cancel-lead-dispute', 'id' => 'cancel_lead_dispute_form'])!!}
{!! Form::hidden('lead_id', null, ['id' => 'lead_id']) !!}
{!! Form::close() !!}


@endsection

@section ('page_js')
@include('company.profile._js')
<script type="text/javascript">
    $(function () {
        var mark_read = "no";
        var lead_id = "";
        
        $(document).on("click", ".expand_link", function () {
            var company_lead_id = $(this).data("company_lead_id");
            var is_checked = $(this).data("is_checked");
            $($(this).data('id')).fadeToggle('slow');

            if (lead_id != company_lead_id && is_checked == 'no') {
                mark_read = "no";
            } else {
                mark_read = "yes";
            }

            if (mark_read == 'no') {
                mark_read = "yes";
                lead_id = company_lead_id;

                $.ajax({
                    url: '{{ url("leads-archive-inbox/mark-lead-as-read") }}',
                    type: 'POST',
                    data: {'company_lead_id': company_lead_id, '_token': '{{ csrf_token() }}'},
                    success: function (data) {}
                });
            }
        });

        /* Lead dispute start */
        $(".generate_lead_dispute").on("click", function () {
            var lead_id = $(this).data('lead_id');

            $("#disputeModal #lead_dispute_form #lead_id").val(lead_id);
        });

        $(".is_phone").on("change", function () {
            if ($(this).val() == 'yes') {
                $("#phone_call_selection").show();
                $("#phone_call_selection select").attr('required', true);
            } else if ($(this).val() == 'no') {
                $("#phone_call_selection").hide();
                $("#phone_call_selection select").attr('required', false);
            }
        });

        $(".is_email").on("change", function () {
            if ($(this).val() == 'yes') {
                $("#email_selection").show();
                $("#email_selection select").attr('required', true);
            } else if ($(this).val() == 'no') {
                $("#email_selection").hide();
                $("#email_selection select").attr('required', false);
            }
        });

        $("#disputeModal").on("hidden.bs.modal", function () {
            $("#email_selection, #phone_call_selection").hide();
            $("#email_selection select, #phone_call_selection select").attr('required', false);
            $(this).find('form').trigger('reset');
        });
        
        $("#lead_dispute_form").on("submit", function (){
            var instance = $(this).parsley();
            if (instance.isValid()){
                $(".lead_dispute_submit_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
                $(".lead_dispute_submit_btn").attr("disabled", true);
            } else {
                $(".lead_dispute_submit_btn").html('Submit');
                $(".lead_dispute_submit_btn").attr("disabled", false);
            }
        });
        /* Lead dispute end */


        /* Leads delete start */
        $('.lead_delete_btn').click(function () {
            $url = $(this).attr('href');
            $('#lead_delete_form').attr('action', $url);
            $('#lead_delete_form #row_id').val($(this).data('id'));

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#ff0000",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, delete it!"
            }).then(function (t) {
                if (typeof t.value != 'undefined') {
                    $('#lead_delete_form').submit();
                }
            })
            return false;
        });
        /* Leads delete end */


        /* Monthly budget update start */
        $(".monthly_budget_type").on("change", function () {
            if ($(this).val() == "Temporary") {
                $("#monthly_budget, #monthly_budget #monthly_budget_text").show();
                $("#monthly_budget_effect").hide();
                $("#monthly_budget_effect input").attr('required', false);

            } else if ($(this).val() == "Permanent") {
                $("#monthly_budget, #monthly_budget #monthly_budget_text").hide();
                $("#monthly_budget_effect").show();
                $("#monthly_budget_effect input").attr('required', true);
            }
        });

        $(".monthly_budget_effect").on("change", function () {
            $("#monthly_budget").show();
            if ($(this).val() == 'Yes') {
                $("#monthly_budget_text").show();
                $("#monthly_budget_input").data("parsley-min", '{{ $current_used_budget }}').attr("data-parsley-min", '{{ $current_used_budget }}');
            } else {
                $("#monthly_budget_text").hide();
                $("#monthly_budget_input").data("parsley-min", '100').attr("data-parsley-min", '100');
            }
        });

        $("#monthlyBudgetModel").on("hidden.bs.modal", function () {
            $("#monthly_budget_effect, #monthly_budget, #monthly_budget #monthly_budget_text").hide();
            $(this).find('form').trigger('reset');
        });
        
        $(".monthly_budget_upgrade_form").on("submit", function (){
            var instance = $(this).parsley();
            if (instance.isValid()){
                $(".monthly_budget_submit_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
                $(".monthly_budget_submit_btn").attr('disabled', true);
            } else {
                $(".monthly_budget_submit_btn").html('Submit');
                $(".monthly_budget_submit_btn").attr('disabled', false);
            }
        });
        /* Monthly budget update end */


        /* cancel lead dispute start */
        $(".cancel_lead_dispute").on("click", function () {
            var lead_id = $(this).data("lead_id");

            $("#cancel_lead_dispute_form #lead_id").val(lead_id);

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#ff0000",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, cancel it!"
            }).then(function (t) {
                if (typeof t.value != 'undefined') {
                    $('#cancel_lead_dispute_form').submit();
                }
            })
            return false;
        });
        /* cancel lead dispute end */


        $('.search_lead_btn').click(function () {
            $('.search_lead_form').slideToggle();
        });

    });
</script>
@endsection