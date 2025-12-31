@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')
@include('flash::message')

<!-- Basic Form Wizard -->
<div class="card-box">
    <h1 class="text-center">{{ $admin_page_title }}</h1>
    <div class="clearfix">&nbsp;</div>

    @include('admin.includes.formErrors')

    {!! Form::open(['url' => url('update-review'), 'id' => 'update_review_form', 'class' => 'module_form ']) !!}
    @php
    $setup_fee = $membership_fee = $total_service_fees = 0;
    $contentArr = (array) json_decode($shopping_cart_obj->content);
    @endphp


    {!! Form::hidden('membership_type', $contentArr['membership_type'], ['id' => 'membership_type']) !!}

    {!! Form::hidden('ownership_type', $contentArr['ownership_type'], ['id' => 'ownership_type']) !!}

    {!! Form::hidden('number_of_owners', $contentArr['number_of_owners'], ['id' => 'number_of_owners']) !!}

    {!! Form::hidden('main_zipcode', $contentArr['main_zipcode'], ['id' => 'main_zipcode', 'required' => true]) !!}
    {!! Form::hidden('temp_zipcode', $contentArr['main_zipcode'], ['id' => 'zipcode', 'required' => false]) !!}
    {!! Form::hidden('mile_range', $contentArr['mile_range'], ['id' => 'mile_range', 'required' => true]) !!}

    <div class="card">
        <div class="card-header bg-primary py-3 text-white">
            <h5 class="card-title mb-0 text-white">Pre-Screen/Background Check & Setup fees</h5>
        </div>

        <div id="setup_fees" class="collapse show">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" width="100%" border="0">
                        <thead>
                            <tr class="xs-hidden">
                                <th>Title</th>
                                <th class="text-right">Price</th>
                                <th class="text-right">Quantity</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($contentArr['promotional_code']))
                                @php
                                $first_owner_fee = (isset($contentArr['first_owner_fee'])) ? $contentArr['first_owner_fee'] : 0;

                                $onetime_setup_fee = (isset($contentArr['onetime_setup_fee'])) ?
                                $contentArr['onetime_setup_fee'] : 0;

                                $setup_fee += ($first_owner_fee + $onetime_setup_fee);
                                @endphp

                                <tr class="xs-hidden">
                                    <td>
                                        <i class="fas fa-user-cog"></i> &nbsp;
                                        Pre-Screen/Background Check fee (1st Owner)
                                    </td>
                                    <td class="text-right">${{ number_format($first_owner_fee, 2) }}</td>
                                    <td class="text-right">
                                        1
                                    </td>
                                    <td class="text-right">${{ number_format($first_owner_fee, 2) }}</td>
                                </tr>
                                
                                <tr class="xs-visible">
                                    <td>
                                        <i class="fas fa-user-cog"></i> &nbsp; Pre-Screen/Background Check fee (1st Owner)
                                        <br />
                                        <b>Price:</b> ${{ number_format($first_owner_fee, 2) }}
                                        <br />
                                        <b>Quantity:</b> 1
                                        <br />
                                        <b>Total:</b> ${{ number_format($first_owner_fee, 2) }}
                                    </td>
                                </tr>

                                @if ($contentArr['number_of_owners'] > 1)
                                    @php
                                    $other_owner_total_fee = (isset($contentArr['other_owner_fee'])) ? $contentArr['other_owner_fee'] : ($pre_screen_settings['prescreen-background-check-fees-other-owner'] * ($contentArr['number_of_owners'] - 1));

                                    $other_owner_fee = ($other_owner_total_fee / ($contentArr['number_of_owners'] - 1));

                                    $setup_fee += $other_owner_total_fee;
                                    @endphp
                                    <tr>
                                        <td><i class="fas fa-user-cog"></i> &nbsp; Pre-Screen/Background Check fee (Other Owners)
                                        </td>
                                        <td class="text-right">
                                            ${{ number_format($other_owner_fee, 2) }}
                                        </td>
                                        <td class="text-right">
                                            {{ ($contentArr['number_of_owners'] - 1) }}
                                        </td>
                                        <td class="text-right">
                                            ${{ number_format($other_owner_total_fee, 2) }}
                                        </td>
                                    </tr>
                                    
                                    <tr class="xs-visible">
                                        <td>
                                            <i class="fas fa-user-cog"></i> &nbsp; Pre-Screen/Background Check fee (Other Owners)
                                            <br />
                                            <b>Price:</b> ${{ number_format($other_owner_fee, 2) }}
                                            <br />
                                            <b>Quantity:</b> {{ ($contentArr['number_of_owners'] - 1) }}
                                            <br />
                                            <b>Total:</b> ${{ number_format($other_owner_total_fee, 2) }}
                                        </td>
                                    </tr>
                                @endif
                            @else
                                @php

                                $first_owner_fee = $pre_screen_settings['prescreen-background-check-fees-1-per-owner'];
                                $onetime_setup_fee = $pre_screen_settings['one-time-setup-fee'];

                                $setup_fee += ($first_owner_fee + $onetime_setup_fee);

                                @endphp
                                <tr class="xs-hidden">
                                    <td>
                                        <i class="fas fa-user-cog"></i>
                                        &nbsp; Pre-Screen/Background Check fee (1st Owner)
                                    </td>
                                    <td class="text-right">${{ number_format($first_owner_fee, 2)}}</td>
                                    <td class="text-right">
                                        @if ($contentArr['number_of_owners'] == 1 && $contentArr['ownership_type'] == 'private')
                                        <a href="javascript:;" data-toggle="modal" data-target="#changeOwner"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
                                        @endif
                                        1
                                    </td>
                                    <td class="text-right">${{ number_format($first_owner_fee, 2)}}</td>
                                </tr>
                                
                                <tr class="xs-visible">
                                    <td>
                                        <i class="fas fa-user-cog"></i> &nbsp; Pre-Screen/Background Check fee (1st Owner)
                                        
                                        <br />
                                        <b>Price:</b> ${{ number_format($first_owner_fee, 2) }}
                                        <br />
                                        <b>Quantity:</b> 
                                        @if ($contentArr['number_of_owners'] == 1 && $contentArr['ownership_type'] == 'private')
                                        <a href="javascript:;" data-toggle="modal" data-target="#changeOwner"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
                                        @endif
                                        1
                                        <br />
                                        <b>Total:</b> ${{ number_format($first_owner_fee, 2) }}
                                    </td>
                                </tr>

                                @if ($contentArr['number_of_owners'] > 1 && $contentArr['ownership_type'] == 'private')
                                    @php
                                    $other_owner_fee = $pre_screen_settings['prescreen-background-check-fees-other-owner'];

                                    $other_owner_total_fee = ($other_owner_fee * ($contentArr['number_of_owners'] - 1));

                                    $setup_fee += $other_owner_total_fee;
                                    @endphp
                                    <tr class="xs-hidden">
                                        <td><i class="fas fa-user-cog"></i> &nbsp; Pre-Screen/Background Check fee (Other Owners)
                                        </td>
                                        <td class="text-right">
                                            ${{ number_format($other_owner_fee, 2) }}
                                        </td>
                                        <td class="text-right">
                                            @if ($contentArr['ownership_type'] == 'private')
                                            <a href="javascript:;" data-toggle="modal" data-target="#changeOwner"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
                                            @endif

                                            {{ ($contentArr['number_of_owners'] - 1) }}

                                        </td>
                                        <td class="text-right">
                                            ${{ number_format($other_owner_total_fee, 2) }}
                                        </td>
                                    </tr>
                                    
                                    <tr class="xs-visible">
                                        <td>
                                            <i class="fas fa-user-cog"></i> &nbsp; Pre-Screen/Background Check fee (Other Owners)
                                            <br />
                                            <b>Price:</b> ${{ number_format($other_owner_fee, 2) }}
                                            <br />
                                            <b>Quantity:</b>
                                            @if ($contentArr['ownership_type'] == 'private')
                                            <a href="javascript:;" data-toggle="modal" data-target="#changeOwner"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
                                            @endif

                                            {{ ($contentArr['number_of_owners'] - 1) }}
                                            <br />
                                            <b>Total:</b> ${{ number_format($other_owner_total_fee, 2) }}
                                        </td>
                                    </tr>
                                @endif
                            @endif

                            <tr class="xs-hidden">
                                <td><i class="fas fa-cogs"></i> &nbsp; One Time Setup Fee</td>
                                <td class="text-right">${{ number_format($onetime_setup_fee, 2) }}</td>
                                <td>&nbsp;</td>
                                <td class="text-right">${{ number_format($onetime_setup_fee, 2) }}</td>
                            </tr>
                            
                            <tr class="xs-visible">
                                <td>
                                    <i class="fas fa-cogs"></i> &nbsp; One Time Setup Fee
                                    <br />
                                    <b>Price:</b> ${{ number_format($onetime_setup_fee, 2) }}
                                    <br />
                                    <b>Total:</b> ${{ number_format($onetime_setup_fee, 2) }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="xs-hidden">
                                <td colspan="4" class="text-right">
                                    <button type="button" class="btn btn-primary">Todays charges Total: <strong>${{ number_format($setup_fee, 2)}}</strong></button>
                                </td>
                            </tr>
                            
                            <tr class="xs-visible">
                                <td>
                                    <button type="button" class="btn btn-primary">Todays charges Total: <strong>${{ number_format($setup_fee, 2)}}</strong></button>
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
                            $membership_fee = $contentArr['membership_fee'];
                            $membership_title = $membership_level_obj->title . ' Fee';
                            @endphp
                            <tr class="xs-hidden">
                                <td><i class="fas fa-users"></i> &nbsp; Annual Membership/Endorsment Fee</td>
                                <?php /* <td class="text-right">${{ number_format($membership_fee, 2) }}</td> */ ?>
                                <td class="text-right">${{ number_format($membership_fee, 2) }}</td>
                            </tr>
                            
                            <tr class="xs-visible">
                                <td>
                                    <i class="fas fa-users"></i> &nbsp; Annual Membership/Endorsment Fee
                                    <br />
                                    <b>Fee:</b> ${{ number_format($membership_fee, 2) }}
                                    <?php /* <br />
                                    ${{ number_format($membership_fee, 2) }} */ ?>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="xs-hidden">
                                <td colspan="2" class="text-right">
                                    <button type="button" class="btn btn-primary">Upon Approval</button>
                                </td>
                            </tr>
                            <tr class="xs-visible">
                                <td>
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
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Listing Type:</label>
                            <span class="">
                                {{ $membership_level_obj->sub_title }}
                                &nbsp;
                                @if ($membership_level_obj->id != 7)
                                @if (!isset($contentArr['promotional_code']))
                                <a href="javascript:;" data-toggle="modal" data-target="#changeMembershipType"><i class="fa fa-edit"></i></a>
                                @endif
                                @endif
                            </span>
                        </div>        
                    </div>

                    @if (isset($contentArr['monthly_budget']) || $contentArr['membership_type'] == 'ppl_price')
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Monthly Budget:</label>
                            <span class="">
                                @if (isset($contentArr['monthly_budget']))
                                ${{ number_format($contentArr['monthly_budget'], 2) }}
                                @else
                                $0.00
                                @endif
                                &nbsp;
                                <a href="javascript:;" data-toggle="modal" data-target="#monthlyBudgetModel"><i class="fa fa-edit"></i></a>
                            </span>
                        </div>
                    </div>
                    @endif
                </div>


                @php $category_type_id = $main_category_id = ""; @endphp

                @if (isset($service_categories) && count($service_categories) > 0)
                <div class="table-responsive">
                    <table class="table mb-0">
                        <tbody>
                            @foreach ($service_categories AS $service_category_item)

                            @if ($category_type_id != $service_category_item->service_category_type_id)
                            <tr class="bg-primary text-white">
                                <th>{{ $service_category_item->service_category_type->title }}</th>
                                @if ($membership_level_obj->id != 7)
                                <th>{{ ($contentArr['membership_type'] == 'ppl_price') ? 'Cost Per Lead' : 'Leads'}}</th>
                                @endif
                                <th>&nbsp;</th>
                            </tr>

                            @php
                            $category_type_id = $service_category_item->service_category_type_id;
                            $main_category_id = "";
                            @endphp
                            @endif

                            @if ($main_category_id != $service_category_item->main_category_id)
                            <tr>
                                <th>{{ $service_category_item->main_category->title }}</th>

                                @php $main_category_id = $service_category_item->main_category_id; @endphp
                                @if ($membership_level_obj->id != 7)
                                <th>
                                    @php
                                    $membership_price = '$0.00';
                                    //$main_category_id = $service_category_item->main_category_id;
                                    $main_service_category_payment_title = "";

                                    if ($service_category_item->main_category_status == 'active' &&
                                    $contentArr['membership_type'] == 'annual_price'){
                                    $price = $service_category_item->main_category->annual_price;

                                    if (!is_null($service_category_item->fee)){
                                    $price = $service_category_item->fee;
                                    }

                                    $total_service_fees+= $price;
                                    $membership_price = '$'.number_format($price, 2);

                                    $main_service_category_payment_title = "Annually";

                                    } else if ($service_category_item->main_category_status == 'active' &&
                                    $contentArr['membership_type'] == 'monthly_price'){
                                    $price = $service_category_item->main_category->monthly_price;

                                    if (!is_null($service_category_item->fee)){
                                    $price = $service_category_item->fee;
                                    }

                                    $total_service_fees+= $price;
                                    $membership_price = '$'.number_format($price, 2);

                                    $main_service_category_payment_title = "Monthly";

                                    } else if ($service_category_item->main_category_status == 'active' &&
                                    $contentArr['membership_type'] == 'ppl_price'){
                                    $membership_price = "";
                                    }
                                    @endphp

                                    {{ $membership_price }}
                                    {{ $main_service_category_payment_title }}
                                </th>
                                @endif

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
                                @if ($membership_level_obj->id != 7)
                                <td>
                                    @php
                                    $ppl_price = "0.00 included";
                                    if ($service_category_item->service_category_status == 'active' &&
                                    $contentArr['membership_type'] == 'ppl_price'){
                                    if (!is_null($service_category_item->fee)){
                                    $ppl_price = $service_category_item->fee;
                                    } else if (!is_null($service_category_item->service_category->ppl_price)){
                                    $ppl_price = $service_category_item->service_category->ppl_price;
                                    } else {
                                    $ppl_price = $service_category_item->main_category->ppl_price;
                                    }

                                    $total_service_fees+= $ppl_price;

                                    $ppl_price = number_format($ppl_price, 2);
                                    } else if ($contentArr['membership_type'] == 'ppl_price'){
                                    $ppl_price = "0.00 Removed";
                                    }
                                    @endphp
                                    ${{ $ppl_price }} 
                                    @if ($contentArr['membership_type'] == 'ppl_price')
                                    Per Lead
                                    @endif
                                </td>
                                @endif
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

                        @if ($membership_level_obj->id != 7)
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right">
                                    @if ($contentArr['membership_type'] == 'ppl_price')


                                    @if (isset($contentArr['monthly_budget']))
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#monthlyBudgetModel">
                                        Monthly Budget: ${{ number_format($contentArr['monthly_budget'], 2) }}
                                        &nbsp;
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#monthlyBudgetModel">
                                        Monthly Budget: $0.00
                                        &nbsp;
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    @endif


                                    @else
                                    <button type="button" class="btn btn-primary">
                                        ${{ number_format($total_service_fees, 2) }}
                                        {{ $membership_level_obj->sub_title }}
                                    </button>
                                    @endif
                                </td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary py-3 text-white">
            <div class="card-widgets">
                <a href="javascript:;" title="Edit Region" data-toggle="modal" data-target="#changeRegion" class="edit_region_btn"><i class="fa fa-edit"></i></a>
            </div>
            <h5 class="card-title mb-0 text-white">Regions</h5>
        </div>

        <div id="regions" class="collapse show">
            <div class="card-body">
                <div class="googlemapborder">
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
                <div class="text-right">
                    <span class="text-danger">
                        <b>{{ $membership_level_obj->sub_title }} Terms</b>
                        <a href="javascript:;" data-toggle="modal" data-target="#membershipTerms" title="{{ $membership_level_obj->sub_title }} Terms"><i class="fas fa-question-circle"></i></a>
                    </span>
                </div>
                
                <div class="clearfix">&nbsp;</div>
                <div class="col-md-6 float-right">
                    <div class="table-responsive">
                        <table class="table" width="100%" border="0">
                            <thead>
                                <tr class="xs-hidden">
                                    <th colspan="2">Todays Charges</th>
                                </tr>
                                <tr class="xs-visible">
                                    <th>Todays Charges</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($contentArr['promotional_code']))
                                    <tr class="xs-hidden">
                                        <td class="text-right">(Prescreen/Background Check Fee for 1st Owner)</td>
                                        <td class="text-right">1 x ${{ number_format($first_owner_fee, 2) }}</td>
                                    </tr>
                                    <tr class="xs-visible">
                                        <td>
                                            (Prescreen/Background Check Fee for 1st Owner) <br />
                                            1 x ${{ number_format($first_owner_fee, 2) }}
                                        </td>
                                    </tr>
                                    @if ($contentArr['number_of_owners'] > 1)
                                    <tr class="xs-hidden">
                                        <td class="text-right">(Prescreen/Background Check Fee for Other Owner)</td>
                                        <td class="text-right">{{ ($contentArr['number_of_owners'] - 1)}} x ${{ number_format($other_owner_fee, 2) }}</td>
                                    </tr>
                                    <tr class="xs-visible">
                                        (Prescreen/Background Check Fee for Other Owner) <br />
                                        {{ ($contentArr['number_of_owners'] - 1)}} x ${{ number_format($other_owner_fee, 2) }}
                                    </tr>
                                    @endif

                                @else 
                                    <tr class="xs-hidden">
                                        <td class="text-right">(Prescreen/Background Check Fee for 1st Owner)</td>
                                        <td class="text-right">1 x ${{ number_format($first_owner_fee, 2) }}</td>
                                    </tr>
                                    <tr class="xs-visible">
                                        <td>
                                            (Prescreen/Background Check Fee for 1st Owner) <br />
                                            1 x ${{ number_format($first_owner_fee, 2) }}
                                        </td>
                                    </tr>
                                    @if ($contentArr['number_of_owners'] > 1 && $contentArr['ownership_type'] == 'private')
                                    <tr class="xs-hidden">
                                        <td class="text-right">(Prescreen/Background Check Fee for Other Owner)</td>
                                        <td class="text-right">{{ ($contentArr['number_of_owners'] - 1)}} x ${{ number_format($other_owner_fee, 2) }}</td>
                                    </tr>
                                    <tr class="xs-visible">
                                        <td>
                                            (Prescreen/Background Check Fee for Other Owner) <br />
                                            {{ ($contentArr['number_of_owners'] - 1)}} x ${{ number_format($other_owner_fee, 2) }}
                                        </td>
                                    </tr>
                                    @endif
                                @endif

                                <tr class="xs-hidden">
                                    <td class="text-right">(One Time Setup Fee)</td>
                                    <td class="text-right">${{ number_format($onetime_setup_fee, 2) }}</td>
                                </tr>
                                <tr class="xs-visible">
                                    <td>
                                        (One Time Setup Fee)<br />
                                        ${{ number_format($onetime_setup_fee, 2) }}
                                    </td>
                                </tr>
                                    
                                <tr class="xs-hidden">
                                    <th class="text-right">Total Charges Today</th>
                                    <th class="text-right">${{ number_format($setup_fee, 2) }}</th>
                                </tr>
                                <tr class="xs-visible">
                                    <th>
                                        Total Charges Today<br />
                                        ${{ number_format($setup_fee, 2) }}
                                    </th>
                                </tr>

                                <tr class="xs-hidden">
                                    <th colspan="2">Charges Upon Approval</th>
                                </tr>
                                <tr class="xs-visible">
                                    <th>Charges Upon Approval</th>
                                </tr>
                                
                                <tr class="xs-hidden">
                                    <td class="text-right">Annual Membership/Endorsment Fee</td>
                                    <td class="text-right">${{ number_format($membership_fee, 2) }}</td>
                                </tr>
                                <tr class="xs-visible">
                                    <td>
                                        Annual Membership/Endorsment Fee <br />
                                        ${{ number_format($membership_fee, 2) }}
                                    </td>
                                </tr>
                                
                                @if ($membership_level_obj->id != 7)
                                <tr class="xs-hidden">
                                    <td class="text-right">{{ $membership_level_obj->charges_on_approval }}</td>
                                    <td class="text-right">
                                        @if ($contentArr['membership_type'] == 'ppl_price')
                                        Monthly Budget Ongoing
                                        @else
                                        ${{ number_format($total_service_fees, 2) }}
                                        @endif
                                    </td>
                                </tr>
                                
                                <tr class="xs-visible">
                                    <td>
                                        {{ $membership_level_obj->charges_on_approval }} <br />
                                        @if ($contentArr['membership_type'] == 'ppl_price')
                                        Monthly Budget Ongoing
                                        @else
                                        ${{ number_format($total_service_fees, 2) }}
                                        @endif
                                    </td>
                                </tr>
                                @endif

                                @php
                                //$total_charge = $setup_fee + $membership_fee + $total_service_fees;

                                if ($contentArr['membership_type'] == 'ppl_price'){
                                $total_charge = $membership_fee;
                                } else {
                                $total_charge = $membership_fee + $total_service_fees;
                                }
                                @endphp
                                <tr class="xs-hidden">
                                    <th class="text-right">Total Charges Upon Approval</th>
                                    <th class="text-right">${{ number_format($total_charge, 2) }}</th>
                                </tr>
                                <tr class="xs-visible">
                                    <th>
                                        Total Charges Upon Approval <br />
                                        ${{ number_format($total_charge, 2) }}
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-right">
                        @if (isset($contentArr['promotional_code']))
                        {!! Form::hidden('first_owner_fee', $first_owner_fee)!!}
                        @if ($contentArr['number_of_owners'] > 1)
                        {!! Form::hidden('other_owner_fee', $other_owner_fee)!!}
                        @endif

                        <?php /* {!! Form::hidden('owner_fee', $owner_fee)!!} */ ?>
                        @else
                        {!! Form::hidden('first_owner_fee', $first_owner_fee)!!}
                        @if ($contentArr['number_of_owners'] > 1 && $contentArr['ownership_type'] == 'private')
                        {!! Form::hidden('other_owner_fee', $other_owner_fee)!!}
                        @endif
                        @endif

                        {!! Form::hidden('onetime_setup_fee', $onetime_setup_fee)!!}
                        {!! Form::hidden('setup_fee', $setup_fee)!!}
                        {!! Form::hidden('membership_fee', $membership_fee)!!}
                        {!! Form::hidden('total_service_fees', $total_service_fees)!!}
                        {!! Form::hidden('total_charge', $total_charge)!!}

                        <button type="submit" id="submit_btn" class="btn btn-primary btn-sm update_review_btn">Continue</button>
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
    <div class="modal-dialog modal-xl">
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white mt-0">Choose Region</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['url' => url('update-cart'), 'class' => 'module_form region_upgrade_form', ]) !!}
            {!! Form::hidden('upgrade_type', 'region_upgrade') !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Zipcode</label>
                            {!! Form::text('main_zipcode', isset($contentArr['main_zipcode']) ?
                            $contentArr['main_zipcode'] : null, ['required' => true, 'class' =>
                            'form-control', 'id' => 'main_zipcode_selection', 'data-toggle' => 'input-mask',
                            'data-mask-format' => '00000']) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Mile Range</label>
                            {!! Form::select('mile_range', config('config.mile_options'),
                            isset($contentArr['mile_range']) ? $contentArr['mile_range'] : null , ['class' =>
                            'form-control', 'id' => 'mile_range_selection', 'required' => true, 'placeholder' => 'Select
                            Zip Radius']) !!}

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div id="zip_code_list">
                            @if (isset($company_zip_codes) && count($company_zip_codes) > 0)
                            <div class="row">
                                @foreach ($company_zip_codes AS $zip_code_item)
                                <div class="col-sm-4">
                                    <ul class="pl20">
                                        <li>
                                            <div class="checkbox checkbox-primary">
                                                <input name="zipcode_item[]" value="{{ $zip_code_item->zip_code }}"
                                                       id="miles_{{ $zip_code_item->zip_code }}" type="checkbox"
                                                       {{ (($zip_code_item->status == 'active') ? 'checked' : '') }} />
                                                <label for="miles_{{ $zip_code_item->zip_code }}">
                                                    {{ $zip_code_item->zip_code.', '.$zip_code_item->city.', ('.$zip_code_item->distance.' miles)' }}
                                                </label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect change_region_btn">Submit</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div id="monthlyBudgetModel" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white mt-0">Monthly Budget</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['url' => url('update-cart'), 'class' => 'module_form monthly_budget_upgrade_form', ]) !!}
            {!! Form::hidden('upgrade_type', 'monthly_budget') !!}
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-search-dollar display-4 m-0"></i>
                    <h4>Please enter a projected monthly budget you intend to spend each month on leads.</h4>
                    <p>You're always in total control of your monthly budget. Monthly budget amount can be increased/decreased and/or leads can be paused/restarted at anytime.</p>
                </div>
                <div class="form-group">
                    <label>Monthly Budget</label>
                    {!! Form::text('monthly_budget', isset($contentArr['monthly_budget']) ? $contentArr['monthly_budget'] : null, ['class' => 'form-control', 'id' => 'monthly_budget', 'placeholder' => 'Enter Monthly Budget Amount($)', 'step' => '0.01', 'data-parsley-min' => isset($contentArr['monthly_budget']) ? $contentArr['monthly_budget'] : 1, 'data-parsley-min-message' => 'Please enter a minimum monthly budget of $'.(isset($contentArr['monthly_budget']) ? number_format($contentArr['monthly_budget'], 2) : '1.00'), 'data-parsley-required-message' => 'Please enter a monthly budget', 'required' => true]) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect update_monthly_budget_btn">Submit</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


@if ($membership_level_obj->terms_content != '')                    
<div class="modal fade" id="membershipTerms" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $membership_level_obj->sub_title }} Terms</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! $membership_level_obj->terms_content !!}
            </div>

        </div>
    </div>
</div>
@endif

@endsection

@section ('page_js')

<script type="text/javascript">
$(function () {
    @if ($contentArr['membership_type'] == 'ppl_price' && (!isset($contentArr['monthly_budget']) || $contentArr['monthly_budget'] == 0))
    $("#monthlyBudgetModel").modal("show");
    @endif
    
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
        
        if (status == 'active') {
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
        }).then(function (t) {
            if (t.value) {
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
                        }).then(function () {
                            window.location.reload();
                        });
                    }
                });
            }
        });
    });
    // Remove Category From Cart [End]


    $('#mile_range_selection').change(function () {
        if ($(this).val() > 0) {
            getGoogleMaps($(this).val());
            $('.change_region_btn').prop('disabled', true).html('Please Wait...');
            var zipcode = $("#main_zipcode_selection").val();
            var mile_range = $(this).val();
            
            $.ajax({
                url: '{{ url("zipcode-list-display") }}',
                type: 'POST',
                data: {'zipcode': zipcode, 'mile_range': mile_range, '_token': '{{ csrf_token() }}'},
                success: function (data) {
                    if (typeof data.status !== 'undefined') {
                        alert(data.message);
                    } else {
                        $("#zip_code_list").html(data);
                    }

                    $('.change_region_btn').prop('disabled', false).html('Submit');
                }
            });
        } else {
            getGoogleMaps('{{ $contentArr["mile_range"] }}');
        }
    });
    
    $(".edit_region_btn").on("click", function (){
        $("#mile_range_selection").trigger("change");
    });
    //$("#mile_range_selection").trigger("change");


    $(".submit-step-2").click(function () {
        var data = {
            '_token': '{{ csrf_token() }}',
            'owners_selection': $(this).data("owners"),
            'upgrade_type': 'owner_upgrade',
        };
        ajaxCall(data);
    });
    
    $(".membership_selection_btn").click(function () {
        var data = {
            '_token': '{{ csrf_token() }}',
            'membership_level_id': $(this).data("membership_level_id"),
            'upgrade_type': 'membership_upgrade',
        };
        ajaxCall(data);
    });
    
    $('.region_upgrade_form').submit(function () {
        var data = $(this).serialize();
        ajaxCall(data);
        
        var instance = $(this).parsley();
        if (instance.isValid()){
            $(".change_region_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
            $(".change_region_btn").attr("disabled", true);
        } else {
            $(".change_region_btn").html('Submit');
            $(".change_region_btn").attr("disabled", false);
        }
        
        return false;
    });
    
    $(document).on("submit", ".monthly_budget_upgrade_form", function () {
        var data = $(this).serialize();
        ajaxCall(data);
        
        var instance = $(this).parsley();
        if (instance.isValid()){
            $(".update_monthly_budget_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
            $(".update_monthly_budget_btn").attr("disabled", true);
        } else {
            $(".update_monthly_budget_btn").html('Submit');
            $(".update_monthly_budget_btn").attr("disabled", false);
        }
        return false;
    });
    
    
    $("#update_review_form").on("submit", function (){
        var instance = $(this).parsley();
        if (instance.isValid()){
            $(".update_review_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
            $(".update_review_btn").attr("disabled", true);
        } else {
            $(".update_review_btn").html('Continue');
            $(".update_review_btn").attr("disabled", false);
        }
    });
    
    @if ($contentArr['membership_type'] == 'ppl_price')
    $("#submit_btn").on("click", function (){
        var monthly_budget = '{{ isset($contentArr['monthly_budget']) ? $contentArr['monthly_budget'] : "0" }}';
        if (monthly_budget <= 0){
            Swal.fire({
                title: 'Monthly Budget',
                type: 'warning',
                text: 'Set monthly budget first.'
            }).then(function () {
                $("#monthlyBudgetModel").modal("show");
            });
            return false;
        }
    });
    @endif
});


function ajaxCall(data) {
    var ajaxPassData = data;
    $.ajax({
        url: '{{ url("update-cart") }}',
        type: 'POST',
        data: data,
        success: function (data) {
            //$(".modal").modal("hide");
            //type: data.type
            
            Swal.fire({
                title: data.title,
                text: data.message,
                type: data.type
                /*imageUrl: '{{ asset("images/checkmark.png") }}',
                imageWidth: 70*/
            }).then(function () {
                if (ajaxPassData.upgrade_type == 'membership_upgrade' && ajaxPassData.membership_level_id == '6') {
                    $("#changeMembershipType").modal("hide");
                    $("#monthlyBudgetModel").modal("show");
                } else {
                    window.location.reload();
                }
            });
        }
    });
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{ env('GOOGLE_MAP_API_KEY') }}"></script>
<script src="{{ asset('js/zipcode-radius.js') }}"></script>
@endsection
