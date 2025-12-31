@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')
@include('flash::message')

<!-- Basic Form Wizard -->
<div class="card-box">
    <h1 class="text-center">{{ $admin_page_title }}</h1>
    <div class="clearfix">&nbsp;</div>

    @include('admin.includes.formErrors')

    {!! Form::open(['url' => url('update-review'), 'id' => 'register_form', 'class' => 'module_form ']) !!}
    @php
    $setup_fee = $membership_fee = $total_service_fees = 0;
    $contentArr = (array) json_decode($shopping_cart_obj->content);
    @endphp


    {!! Form::hidden('membership_type', $contentArr['membership_type'], ['id' => 'membership_type']) !!}

    {!! Form::hidden('company_ownership', $contentArr['company_ownership'], ['id' => 'company_ownership']) !!}

    {!! Form::hidden('number_of_owners', $contentArr['number_of_owners'], ['id' => 'number_of_owners']) !!}

    {!! Form::hidden('main_zipcode', $contentArr['main_zipcode'], ['id' => 'main_zipcode']) !!}
    {!! Form::hidden('mile_range', $contentArr['mile_range'], ['id' => 'mile_range']) !!}

    <div class="card">
        <div class="card-header bg-primary py-3 text-white">
            <h5 class="card-title mb-0 text-white">Pre-Screen/Background Check & Setup fees</h5>
        </div>

        <div id="setup_fees" class="collapse show">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" width="100%" border="0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th class="text-right">Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($contentArr['promotional_code']))
                            @php
                            $owner_fee = (isset($contentArr['bg_pre_screen_fee'])) ? $contentArr['bg_pre_screen_fee'] :
                            0;

                            $onetime_setup_fee = (isset($contentArr['onetime_setup_fee'])) ?
                            $contentArr['onetime_setup_fee'] : 0;

                            $setup_fee += ($owner_fee + $onetime_setup_fee);
                            @endphp

                            <tr>
                                <td>
                                    <i class="fas fa-user-cog"></i> &nbsp;
                                    Pre-Screen/Background Check fee ({{ $contentArr['number_of_owners'] }} Owner)
                                </td>
                                <td class="text-right">£{{ number_format($owner_fee, 2) }}</td>
                                <td class="text-center">
                                    1 &nbsp;


                                    <?php /* @if ($contentArr['number_of_owners'] == 1 && $contentArr['company_ownership'] ==
                                        'private')
                                        <a href="javascript:;" data-toggle="modal" data-target="#changeOwner"><i
                                                class="fa fa-edit"></i></a>
                                        @endif */ ?>
                                </td>
                                <td class="text-right">£{{ number_format($owner_fee, 2) }}</td>
                            </tr>
                            @else
                            @php

                            $first_owner_fee = (isset($company_charge_setting['1st-owner-background-check-fee'])) ?
                            $company_charge_setting['1st-owner-background-check-fee'] : 0;

                            $onetime_setup_fee = (isset($company_charge_setting['one-time-setup-fee'])) ?
                            $company_charge_setting['one-time-setup-fee'] : 0;

                            $setup_fee += ($first_owner_fee + $onetime_setup_fee);

                            @endphp
                            <tr>
                                <td><i class="fas fa-user-cog"></i> &nbsp; Pre-Screen/Background Check fee (1st Per
                                    Owner)
                                </td>
                                <td class="text-right">£{{ number_format($first_owner_fee, 2)}}</td>
                                <td class="text-center">
                                    1 &nbsp;
                                    @if ($contentArr['number_of_owners'] == 1 && $contentArr['company_ownership'] ==
                                    'private')
                                    <a href="javascript:;" data-toggle="modal" data-target="#changeOwner"><i
                                            class="fa fa-edit"></i></a>
                                    @endif
                                </td>
                                <td class="text-right">£{{ number_format($first_owner_fee, 2)}}</td>
                            </tr>

                            @if ($contentArr['number_of_owners'] > 1 && $contentArr['company_ownership'] == 'private')
                            @php
                            $other_owner_fee = (isset($company_charge_setting['other-owner-background-check-fee'])) ?
                            $company_charge_setting['other-owner-background-check-fee'] : 0;

                            $other_owner_total_fee = ($other_owner_fee * ($contentArr['number_of_owners'] - 1));

                            $setup_fee += $other_owner_total_fee;
                            @endphp
                            <tr>
                                <td><i class="fas fa-user-cog"></i> &nbsp; Pre-Screen/Background Check fee (Other
                                    Owners)
                                </td>
                                <td class="text-right">
                                    £{{ number_format($other_owner_fee, 2) }}
                                </td>
                                <td class="text-center">
                                    {{ ($contentArr['number_of_owners'] - 1) }} &nbsp;
                                    @if ($contentArr['company_ownership'] == 'private')
                                    <a href="javascript:;" data-toggle="modal" data-target="#changeOwner"><i
                                            class="fa fa-edit"></i></a>
                                    @endif
                                </td>
                                <td class="text-right">
                                    £{{ number_format($other_owner_total_fee, 2) }}
                                </td>
                            </tr>
                            @endif
                            @endif


                            <tr>
                                <td><i class="fas fa-cogs"></i> &nbsp; One Time Setup Fee</td>
                                <td class="text-right">£{{ number_format($onetime_setup_fee, 2) }}</td>
                                <td>&nbsp;</td>
                                <td class="text-right">£{{ number_format($onetime_setup_fee, 2) }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right">
                                    <button type="button" class="btn btn-primary">Todays charges Total:
                                        £{{ number_format($setup_fee, 2)}}</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary py-3 text-white">
            <h5 class="card-title mb-0 text-white">Membership fees</h5>
        </div>

        <div id="membership_fees" class="collapse show">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" width="100%" border="0">
                        <tbody>
                            @php
                            if ($contentArr['membership_type'] == 'annual_membership'){
                            $membership_title = "Annual Membership Fee";

                            $membership_fee = (isset($company_charge_setting['annual-membership-fee'])) ?
                            $company_charge_setting['annual-membership-fee'] : 0.00;

                            } else if ($contentArr['membership_type'] == 'monthly_membership'){
                            $membership_title = "Monthly Membership Fee";

                            $membership_fee = (isset($company_charge_setting['monthly-membership-fee'])) ?
                            $company_charge_setting['monthly-membership-fee'] : 0.00;

                            } else if ($contentArr['membership_type'] == 'ppl_membership'){
                            $membership_title = "Pay per leads Fee";

                            $membership_fee = (isset($company_charge_setting['ppl-membership-fee'])) ?
                            $company_charge_setting['ppl-membership-fee'] : 0.00;

                            }
                            @endphp
                            <tr>
                                <td><i class="fas fa-users"></i> &nbsp; {{ $membership_title }}</td>
                                <td class="text-right">£{{ number_format($membership_fee, 2) }}</td>
                                <td class="text-right">£{{ number_format($membership_fee, 2) }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right">
                                    <button type="button" class="btn btn-primary">Upon Approval</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary py-3 text-white">
            <h5 class="card-title mb-0 text-white">Selected Listings</h5>
        </div>

        <div id="selected_listings" class="collapse show">
            <div class="card-body">
                <div class="form-group">
                    <label>Listing Type:</label>
                    <span class="">
                        @php $list_type_text = ""; @endphp
                        @if ($contentArr['membership_type'] == 'annual_membership')
                        @php $list_type_text = "Annual Listing - Unlimited Leads"; @endphp
                        @elseif ($contentArr['membership_type'] == 'monthly_membership')
                        @php $list_type_text = "Monthly Listing - Unlimited Leads"; @endphp
                        @elseif ($contentArr['membership_type'] == 'ppl_membership')
                        @php $list_type_text = "Pay per leads Listing"; @endphp
                        @endif

                        {{ $list_type_text }}
                        &nbsp;

                        @if (!isset($contentArr['promotional_code']))
                        <a href="javascript:;" data-toggle="modal" data-target="#changeMembershipType"><i
                                class="fa fa-edit"></i></a>
                        @endif
                    </span>
                </div>

                @php $category_type_id = $main_category_id = ""; @endphp

                @if (isset($service_categories) && count($service_categories) > 0)
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            @foreach ($service_categories AS $service_category_item)

                            @if ($category_type_id != $service_category_item->service_category_type_id)
                                <tr class="bg-primary text-white">
                                    <th>{{ $service_category_item->service_category_type->title }}</th>
                                    <th>Leads</th>
                                    <th>&nbsp;</th>
                                </tr>

                                @php
                                    $category_type_id = $service_category_item->service_category_type_id;
                                @endphp
                            @endif

                            @if ($main_category_id != $service_category_item->main_category_id)
                                <tr>
                                    <th>{{ $service_category_item->main_category->title }}</th>
                                    <th>
                                        @php
                                            $membership_price = '£0.00';
                                            $main_category_id = $service_category_item->main_category_id;
                                            $main_service_category_payment_title = "";

                                            if ($service_category_item->main_category_status == 'active' && $contentArr['membership_type'] == 'annual_membership'){
                                                $price = $service_category_item->main_category->annual_price;

                                                if (!is_null($service_category_item->fee)){
                                                    $price = $service_category_item->fee;
                                                }
                                                
                                                $total_service_fees+= $price;
                                                $membership_price = '£'.number_format($price, 2);

                                                $main_service_category_payment_title = "Annually";

                                            } else if ($service_category_item->main_category_status == 'active' && $contentArr['membership_type'] == 'monthly_membership'){
                                                $price = $service_category_item->main_category->monthly_price;

                                                if (!is_null($service_category_item->fee)){
                                                    $price = $service_category_item->fee;
                                                }

                                                $total_service_fees+= $price;
                                                $membership_price = '£'.number_format($price, 2);

                                                $main_service_category_payment_title = "Monthly";

                                            } else if ($service_category_item->main_category_status == 'active' && $contentArr['membership_type'] == 'ppl_membership'){
                                                $membership_price = "";
                                            }
                                        @endphp

                                        {{ $membership_price }}
                                        {{ $main_service_category_payment_title }}
                                    </th>

                                    <td class="text-right">
                                        @if ($service_category_item->main_category_status == 'active')
                                        <a href="javascript:;" title="Remove" class="text-danger remove_category"
                                            data-type="main_category" data-status='inactive'
                                            data-service_category_type_id="{{ $service_category_item->service_category_type_id }}"
                                            data-id="{{ $service_category_item->main_category_id }}">
                                            <i class="far fa-window-close"></i>
                                        </a>
                                        @else
                                        <span class="text-danger">
                                            Removed
                                        </span>

                                        <a href="javascript:;" title="Remove" class="text-danger remove_category"
                                            data-type="main_category" data-status='active'
                                            data-service_category_type_id="{{ $service_category_item->service_category_type_id }}"
                                            data-id="{{ $service_category_item->main_category_id }}">
                                            <i class="fas fa-undo"></i>
                                        </a>

                                        @endif
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td>{{ $service_category_item->service_category->title }}</td>
                                <td>
                                    @php
                                    $ppl_price = "0.00 included";
                                    if ($service_category_item->service_category_status == 'active' &&
                                    $contentArr['membership_type'] == 'ppl_membership'){
                                    if (!is_null($service_category_item->fee)){
                                    $ppl_price = $service_category_item->fee;
                                    } else if (!is_null($service_category_item->service_category->ppl_price)){
                                    $ppl_price = $service_category_item->service_category->ppl_price;
                                    } else {
                                    $ppl_price = $service_category_item->main_category->ppl_price;
                                    }

                                    $total_service_fees+= $ppl_price;

                                    $ppl_price = number_format($ppl_price, 2);
                                    } else if ($contentArr['membership_type'] == 'ppl_membership'){
                                    $ppl_price = "0.00 Removed";
                                    }
                                    @endphp
                                    £{{ $ppl_price }}
                                </td>
                                <td class="text-right">
                                    @if ($service_category_item->service_category_status == 'active')
                                    <a href="javascript:;" title="Remove" class="text-danger remove_category"
                                        data-type="service_category" data-status='inactive'
                                        data-main_category_id="{{ $service_category_item->main_category_id }}"
                                        data-service_category_type_id="{{ $service_category_item->service_category_type_id }}"
                                        data-id="{{ $service_category_item->service_category_id }}">
                                        <i class="far fa-window-close"></i>
                                    </a>
                                    @else
                                    <span class="text-danger">Removed</span>

                                    <a href="javascript:;" title="Remove" class="text-danger remove_category"
                                        data-type="service_category" data-status='active'
                                        data-main_category_id="{{ $service_category_item->main_category_id }}"
                                        data-service_category_type_id="{{ $service_category_item->service_category_type_id }}"
                                        data-id="{{ $service_category_item->service_category_id }}"><i
                                            class="fas fa-undo"></i></a>

                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right">
                                    <button type="button" class="btn btn-primary">
                                        £{{ number_format($total_service_fees, 2) }}
                                        {{ $list_type_text }}
                                    </button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @endif

                <div class="text-right">
                    <span class="text-danger">
                        <b>Annual Listing Terms</b>
                        <a href="#"><i class="fas fa-question-circle"></i></a>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary py-3 text-white">
            <div class="card-widgets">
                <a href="javascript:;" title="Edit Region" data-toggle="modal" data-target="#changeRegion"> <i
                        class="fa fa-edit"></i></a>
            </div>
            <h5 class="card-title mb-0 text-white">Regions</h5>
        </div>

        <div id="regions" class="collapse show">
            <div class="card-body">
                <div class="googlemapborder">
                    {!! Form::hidden ('mile_range', $contentArr['mile_range'], ['id' => 'mile_range', 'required' =>
                    true] ) !!}

                    {!! Form::hidden ('zipcode', $contentArr['main_zipcode'], ['id' => 'zipcode', 'required' => true] )
                    !!}

                    <div id="map-canvas" style="height:300px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary py-3 text-white">
            <h5 class="card-title mb-0 text-white">Total Charges</h5>
        </div>

        <div id="total_charges" class="collapse show">
            <div class="card-body">
                <div class="col-md-6 float-right">
                    <div class="table-responsive">
                        <table class="table" width="100%" border="0">
                            <thead>
                                <tr>
                                    <th colspan="2">Todays Charges</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if (isset($contentArr['promotional_code']))
                                <tr>
                                    <td class="text-right">(Prescreen/Background Check Fee
                                        {{ $contentArr['number_of_owners'] }} Owner)</td>
                                    <td class="text-right">£{{ number_format($owner_fee, 2) }}</td>
                                </tr>
                                @else
                                <tr>
                                    <td class="text-right">(Prescreen/Background Check Fee for 1st Owner)</td>
                                    <td class="text-right">1 x £{{ number_format($first_owner_fee, 2) }}</td>
                                </tr>
                                @if ($contentArr['number_of_owners'] > 1 && $contentArr['company_ownership'] ==
                                'private')
                                <tr>
                                    <td class="text-right">(Prescreen/Background Check Fee for Other Owner)</td>
                                    <td class="text-right">{{ ($contentArr['number_of_owners'] - 1)}} x
                                        £{{ number_format($other_owner_fee, 2) }}</td>
                                </tr>
                                @endif
                                @endif


                                <tr>
                                    <td class="text-right">(One Time Setup Fee)</td>
                                    <td class="text-right">£{{ number_format($onetime_setup_fee, 2) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Total Chrages Today</th>
                                    <th class="text-right">£{{ number_format($setup_fee, 2) }}</th>
                                </tr>

                                <tr>
                                    <th colspan="2">Chrages Upon Approval</th>
                                </tr>
                                <tr>
                                    <td class="text-right">{{ $membership_title }}</td>
                                    <td class="text-right">£{{ number_format($membership_fee, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">{{ $list_type_text }}</td>
                                    <td class="text-right">£{{ number_format($total_service_fees, 2) }}</td>
                                </tr>

                                @php
                                $total_charge = $setup_fee + $membership_fee + $total_service_fees;
                                @endphp
                                <tr>
                                    <th class="text-right">Total Charges Upon Approval</th>
                                    <th class="text-right">£{{ number_format($total_charge, 2) }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-right">
                        @if (isset($contentArr['promotional_code']))
                        {!! Form::hidden('owner_fee', $owner_fee)!!}
                        @else
                        {!! Form::hidden('first_owner_fee', $first_owner_fee)!!}
                        @if ($contentArr['number_of_owners'] > 1 && $contentArr['company_ownership'] == 'private')
                        {!! Form::hidden('other_owner_fee', $other_owner_fee)!!}
                        @endif
                        @endif

                        {!! Form::hidden('onetime_setup_fee', $onetime_setup_fee)!!}
                        {!! Form::hidden('setup_fee', $setup_fee)!!}
                        {!! Form::hidden('membership_fee', $membership_fee)!!}
                        {!! Form::hidden('total_service_fees', $total_service_fees)!!}
                        {!! Form::hidden('total_charge', $total_charge)!!}

                        <button type="submit" class="btn btn-primary btn-sm">Continue</button>
                    </div>

                    <div class="clearfix">&nbsp;</div>
                </div>
            </div>
        </div>
    </div>

    {!! Form::close() !!}

</div>


<div id="changeOwner" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white mt-0">Company Ownership</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                @include('company.profile.upgrade._owner_selection')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<div id="changeMembershipType" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white mt-0">Choose your perfect plan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                @include('company.profile.upgrade._membership_plan_selection')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<div id="changeRegion" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white mt-0">Choose Region</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['url' => url('update-cart'), 'class' => 'module_form region_upgrade_form', 'onsubmit' =>
            'return false;']) !!}
            {!! Form::hidden('upgrade_type', 'region_upgrade') !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Zipcode</label>
                            {!! Form::text('main_zipcode', isset($contentArr['main_zipcode']) ?
                            $contentArr['main_zipcode'] : null, ['id' => 'zipcode', 'required' => true, 'class' =>
                            'form-control', 'data-toggle' => 'input-mask', 'data-mask-format' => '00000']) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Mile Range</label>
                            {!! Form::select('mile_range', config('config.mile_options'),
                            isset($contentArr['mile_range']) ? $contentArr['mile_range'] : null , ['class' =>
                            'form-control', 'required'
                            => true, 'placeholder' => 'Select Zip Radius']) !!}

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary waves-effect change_region_btn">Submit</button>
            </div>
            {!! Form::close() !!}
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@endsection

@section ('page_js')


<script type="text/javascript">
    $(function () {
    // Remove Category From Cart [Start]
    $(".remove_category").on("click", function () {
        var category_id = $(this).data("id");
        var category_type = $(this).data("type");
        var service_category_type_id = $(this).data("service_category_type_id");
        var main_category_id = $(this).data("main_category_id");
        var status = $(this).data("status");

        popup_message = 'Are you sure you want to remove this category?';
        button_text = 'Yes remove it!';
        confirmButtonColor = '#ff0000';
        if (status == 'active'){
            popup_message = 'Are you sure you want to add this category?';
            button_text = 'Yes add it!';
            confirmButtonColor = "#348cd4";
        }


        Swal.fire({
            title: "Are you sure?",
            text: popup_message,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: confirmButtonColor,
            confirmButtonText: button_text
        }).then(function (t){
            if (t.value){
                $.ajax({
                    url: '{{ url("remove-category-from-cart") }}',
                    type: 'POST',
                    data: {
                        'category_id': category_id,
                        'category_type': category_type,
                        'service_category_type_id': service_category_type_id,
                        'main_category_id': main_category_id,
                        '_token': '{{ csrf_token() }}',
                        'status': status
                    },
                    success: function (data) {
                        Swal.fire({
                            title: data.title,
                            text: data.message,
                            type: data.type
                        }).then(function (){
                            window.location.reload();
                        });
                    }
                });
            }
        });
    });
    // Remove Category From Cart [End]


    $('#mile_range').change(function() {
        if ($(this).val() > 0) {
            getGoogleMaps($(this).val());
        } else {
            getGoogleMaps(1);
        }
    });

    $("#mile_range").trigger("change");


    $(".submit-step-2").click(function(){
        var owners_selection = $(this).data("owners");
        var upgrade_type = "owner_upgrade";

        if (typeof owners_selection !== 'undefined' && owners_selection != ''){
            ajaxCall (upgrade_type, owners_selection);
        } else {
            Swal.fire({
                title: "Error",
                text: "Please select owner first.",
                type: "error"
            });
        }
    });

    $(".membership_selection_btn").click(function(){
        var membership_type = $(this).data("type");
        var upgrade_type = "membership_upgrade";

        if (typeof membership_type !== 'undefined' && membership_type != ''){
            ajaxCall (upgrade_type, membership_type);
        } else {
            Swal.fire({
                title: "Error",
                text: "Please select membership type first.",
                type: "error"
            });
        }
    });

    $(".change_region_btn").on("click", function (){
        var form_url = $(this).parents("form").attr("action");
        var form_elements = $(this).parents("form").serialize();

        ajaxCall ("region_upgrade", form_elements);
    });
});


function ajaxCall (upgrade_type, upgrade_option){
    var dataString = "";
    if (upgrade_type == 'membership_upgrade'){
        dataString = "membership_type="+upgrade_option+"&upgrade_type="+upgrade_type+"&_token={{ csrf_token() }}";
    } else if (upgrade_type == 'owner_upgrade'){
        dataString = "owners_selection="+upgrade_option+"&upgrade_type="+upgrade_type+"&_token={{ csrf_token() }}";
    } else if (upgrade_type == 'region_upgrade'){
        var dataString = upgrade_option;
    }

    $.ajax({
        url: '{{ url("update-cart") }}',
        type: 'POST',
        data: dataString,
        success: function (data){
            Swal.fire({
                title: data.title,
                text: data.message,
                type: data.type
            }).then(function (){
                window.location.reload();
            });
        }
    });
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{ env('GOOGLE_MAP_API_KEY') }}"></script>
<script src="{{ asset('js/zipcode-radius.js') }}"></script>
@endsection
