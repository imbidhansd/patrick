@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')
@include('flash::message')

<!-- Basic Form Wizard -->
<div class="card-box">
    <h1 class="text-center">{{ $admin_page_title }}</h1>
    <div class="clearfix">&nbsp;</div>

    @include('admin.includes.formErrors')

    @php
    $contentArr = (array) json_decode($shopping_cart_obj->content);
    @endphp

    {!! Form::open(['url' => url('account/upgrade/checkout'), 'class' => 'module_form', 'id' => 'checkout_form']) !!}

    {!! Form::hidden('company_id', $companyObj->id) !!}
    {!! Form::hidden('setup_fee', $contentArr['setup_fee']) !!}
    {!! Form::hidden('payment_option', 'credit_card') !!}

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary py-3 text-white">
                    <h5 class="card-title mb-0 text-white">Summary</h5>
                </div>

                <div id="summary" class="collapse show">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0" width="100%" border="0">
                                <tr>
                                    <th class="bt-none" colspan="2">Todays Charges</th>
                                </tr>
                                <tr>
                                    <td class="text-right">(Prescreen/Background Check Fee for 1st Owner)</td>
                                    <td class="text-right" width="30%">1 x ${{ number_format($contentArr['first_owner_fee'], 2) }}</td>
                                </tr>
                                @if ($contentArr['number_of_owners'] > 1)
                                <tr>
                                    <td class="text-right bt-none">(Prescreen/Background Check Fee for Other Owner)</td>
                                    <td class="text-right bt-none">{{ ($contentArr['number_of_owners'] - 1)}} x
                                        ${{ number_format($contentArr['other_owner_fee'], 2) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="text-right bt-none">(One Time Setup Fee)</td>
                                    <td class="text-right bt-none">${{ number_format($contentArr['onetime_setup_fee'], 2) }}</td>
                                </tr>
                                <tr>
                                    <th class="bt-none">Total Charges Today</th>
                                    <th class="text-right bt-none">${{ number_format($contentArr['setup_fee'], 2) }}</th>
                                </tr>
                            </table>
                            
                            <table class="table mb-0" width="100%" border="0">
                                <tr>
                                    <th colspan="2">Charges Upon Approval</th>
                                </tr>
                                <tr>
                                    <td class="text-right">Annual Membership/Endorsment Fee</td>
                                    <td class="text-right" width="30%">${{ number_format($contentArr['membership_fee'], 2) }}</td>
                                </tr>
                                @if ($membership_level_obj->id != 7)
                                <tr>
                                    <td class="text-right bt-none">{{ $membership_level_obj->charges_on_approval }}</td>
                                    <td class="text-right bt-none">
                                        @if ($contentArr['membership_type'] == 'ppl_price')
                                        Monthly Budget Ongoing
                                        @else
                                        ${{ number_format($contentArr['total_service_fees'], 2) }}
                                        @endif
                                    </td>
                                </tr>
                                @endif

                                @php $product_fee = 0; @endphp
                                @if (isset($contentArr['suggested_products']))
                                <tr>
                                    <td class="text-right bt-none">
                                        Digital Product(s) <br />
                                        @foreach ($contentArr['suggested_products'] AS $product_item)
                                        <span>{{ $product_item->title }} -</span> (${{ number_format($product_item->price, 2) }}) <br />

                                        @php $product_fee += $product_item->price; @endphp
                                        @endforeach
                                    </td>
                                    <td class="text-right bt-none">${{ number_format($product_fee, 2)}}</td>
                                </tr>
                                @endif

                                @php
                                $charge_on_approval = $contentArr['membership_fee'] + $contentArr['total_service_fees'] + $product_fee;
                                @endphp
                                <tr>
                                    <th class="bt-none">Total Charges Upon Approval</th>
                                    <th class="text-right bt-none">${{ number_format($charge_on_approval, 2) }}</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary py-3 text-white">
                    <h5 class="card-title mb-0 text-white">Address Details</h5>
                </div>

                <div id="address_details" class="collapse show">
                    <div class="card-body">
                        <h5 class="mt-0">Credit Card Billing Address</h5>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Company Name</label>
                                    {!! Form::text('bill[company_name]', null, ['class' => 'form-control', 'id' => 'bill_company_name', 'required' => true]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>First Name</label>
                                    {!! Form::text('bill[first_name]', null, ['class' => 'form-control', 'id' => 'bill_first_name', 'required' => true]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Last Name</label>
                                    {!! Form::text('bill[last_name]', null, ['class' => 'form-control', 'id' => 'bill_last_name', 'required' => true]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mailing Address</label>
                                    {!! Form::text('bill[mailing_address]', null, ['class' => 'form-control', 'id' => 'bill_mailing_address', 'maxlength' => 60, 'required' => true]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Suite</label>
                                    {!! Form::text('bill[suite]', null, ['class' => 'form-control', 'id' => 'bill_suite', 'required' => false]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>City</label>
                                    {!! Form::text('bill[city]', null, ['class' => 'form-control', 'id' => 'bill_city', 'required' => true]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>State</label>
                                    {!! Form::select('bill[state_id]', $states, null, ['class' => 'form-control custom-select', 'id' => 'bill_state_id', "placeholder" => "State", 'required' => true]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Zipcode</label>
                                    {!! Form::text('bill[zipcode]', null, ['class' => 'form-control', 'id' => 'bill_zipcode', 'data-toggle' => 'input-mask', 'data-mask-format' => '00000', 'required' => true]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    {!! Form::text('bill[phone]', null, ['class' => 'form-control', 'id' => 'bill_phone', 'required' => true , 'maxlength' => 255, 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="clearfix">&nbsp;</div>

                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="mt-2 shipping_inline_display">Shipping Address</h5>
                                &nbsp;&nbsp;
                                <div class="checkbox checkbox-circle checkbox-primary mt-2 shipping_inline_display">
                                    <input type="checkbox" class="checkout_same_address" id="same_as" />
                                    <label for="same_as">Same as Billing?</label>
                                </div>
                            </div>
                        </div>

                        <div id="shipping_address">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Company Name</label>
                                        {!! Form::text('ship[company_name]', null, ['class' => 'form-control', 'id' => 'ship_company_name', 'required' => true]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        {!! Form::text('ship[first_name]', null, ['class' => 'form-control', 'id' => 'ship_first_name', 'required' => true]) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        {!! Form::text('ship[last_name]', null, ['class' => 'form-control', 'id' => 'ship_last_name', 'required' => true]) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mailing Address</label>
                                        {!! Form::text('ship[mailing_address]', null, ['class' => 'form-control', 'id' => 'ship_mailing_address', 'maxlength' => 60, 'required' => true]) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Suite</label>
                                        {!! Form::text('ship[suite]', null, ['class' => 'form-control', 'id' => 'ship_suite', 'required' => false]) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>City</label>
                                        {!! Form::text('ship[city]', null, ['class' => 'form-control', 'id' => 'ship_city', 'required' => true]) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>State</label>
                                        {!! Form::select('ship[state_id]', $states, null, ['class' => 'form-control custom-select', 'id' => 'ship_state_id', "placeholder" => "State", 'required' => true]) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Zipcode</label>
                                        {!! Form::text('ship[zipcode]', null, ['class' => 'form-control', 'id' => 'ship_zipcode', 'data-toggle' => 'input-mask', 'data-mask-format' => '00000', 'required' => true]) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        {!! Form::text('ship[phone]', null, ['class' => 'form-control', 'id' => 'ship_phone', 'required' => true , 'maxlength' => 255, 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">

            <div class="card" id="credit_card_payment_detail">
                <div class="card-header bg-primary py-3 text-white">
                    <h5 class="card-title mb-0 text-white">Payment Detail</h5>
                </div>

                <div id="payment_detail" class="collapse show">
                    <div class="card-body">
                        <div class="row">
                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label>Card Holder Name:*</label>
                                    {!! Form::text('card_name', null, ['class' => 'form-control', 'id' => 'card_name', 'placeholder' => 'Card Holder Name', 'required' => true]) !!}
                                </div>
                            </div> -->
                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('Card Number:*') !!}
                                    {!! Form::text('card_number', null, ['class' => 'form-control', 'placeholder' => 'Enter Card Number', 'maxlength' => 16, 'data-parsley-type' => 'integer', 'id' => 'card', 'required' => true, 'data-toggle' => 'input-mask', 'data-mask-format' => '0000000000000000']) !!}
                                </div>
                            </div> -->
                            <!-- <div class="col-md-9">
                                <div class="form-group">
                                    {!! Form::label('Exp Month (MM/YYYY):*') !!}

                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::select('exp_month', $exp_month_list , null, ['class' => 'form-control custom-select', 'placeholder' => 'Enter Exp Month', 'id' => 'expiration-month', 'required' => true]) !!}
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::select('exp_year',$exp_year_list, null, ['class' => 'form-control custom-select', 'placeholder' => 'Select Exp Year', 'id' => 'expiration-year', 'required' => true]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div> -->

                            <!-- <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('CVC') !!}
                                    {!! Form::tel('cvv', null, ['class' => 'form-control', 'data-parsley-maxlength' => 4, 'placeholder' => 'Enter CVC', 'data-parsley-type' => 'integer', 'id' => 'cvc', 'required' => true, 'data-toggle' => 'input-mask', 'data-mask-format' => '000']) !!}
                                </div>
                            </div> -->


                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Terms & Conditions*</label>

                                    <div class="checkbox checkbox-primary checkbox-circle">
                                        <input type="checkbox" id="terms" name="terms" value="term_yes" required="required" />
                                        <label for="terms">
                                            I have read and agree to the
                                            <a href="javascript:;" data-toggle="modal" data-target="#backgroundCheckPageModal">Background Check/Credit Check Requirement</a>
                                        </label>
                                    </div>

                                    <div class="checkbox checkbox-primary checkbox-circle">
                                        <input type="checkbox" id="terms1" name="terms1" value="term1_yes" required="required" />
                                        <label for="terms1">
                                            I have read and agree to the
                                            <a href="javascript:;" data-toggle="modal" data-target="#termsUsePageModal">Terms of Use</a>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="table-responsive">
                            <table class="table" width="100%" border="0">
                                <tr>
                                    <td>Today's Charges</td>
                                    <td class="text-right">
                                        <strong>${{ number_format($contentArr['setup_fee'], 2) }}</strong>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-block checkout_btn">
                                <i class="fas fa-credit-card"></i>
                                &nbsp; Pay Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {!! Form::close() !!}
</div>


@if (!is_null($background_check_page))                    
<div class="modal fade" id="backgroundCheckPageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $background_check_page->title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! $background_check_page->content !!}
            </div>

        </div>
    </div>
</div>
@endif

@if (!is_null($terms_use_page))                    
<div class="modal fade" id="termsUsePageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $terms_use_page->title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! $terms_use_page->content !!}
            </div>

        </div>
    </div>
</div>
@endif


@endsection

@section ('page_js')
@include('company.invoices._checkout_payment_js')
<script type="text/javascript">
    $(function (){
        $(".checkout_btn").on("click", function (){
            var instance = $("#checkout_form").parsley();
            if (!instance.isValid()){
                $("#same_as").prop("checked", false);
                $("#shipping_address").slideDown();
            }
        });
        
        $("#checkout_form").on("submit", function (){
            var instance = $(this).parsley();
            if (instance.isValid()){
                $(".checkout_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
                $(".checkout_btn").attr("disabled", true);
            } else {
                $(".checkout_btn").html('<i class="fas fa-credit-card"></i>&nbsp; Pay Now');
                $(".checkout_btn").attr("disabled", false);
            }
        });
    });
    function initAutocomplete() {
        //Bill Address autocomplete
        let billAddressField = document.querySelector("#bill_mailing_address");                       
        billAddressAutocomplete = new google.maps.places.Autocomplete(billAddressField, {
            componentRestrictions: { country: ["us"] },
            fields: ["address_components"],
            types: ["address"],
        });
        billAddressField.focus();          
        billAddressAutocomplete.addListener("place_changed", fillInBillAddress);

        //Ship Address autocomplete
        let shipAddressField = document.querySelector("#ship_mailing_address");                       
        shipAddressAutocomplete = new google.maps.places.Autocomplete(shipAddressField, {
            componentRestrictions: { country: ["us"] },
            fields: ["address_components"],
            types: ["address"],
        });
        //billAddressField.focus();          
        shipAddressAutocomplete.addListener("place_changed", fillInShipAddress);
    }
    function fillInBillAddress() {
        const place = billAddressAutocomplete.getPlace(); 

        for (const component of place.address_components) {
            const componentType = component.types[0];
            switch (componentType) {    
                case "postal_code": {
                    document.querySelector("#bill_zipcode").value = component.long_name;
                    break;
                }                
                case "locality": {
                    document.querySelector("#bill_city").value = component.long_name;
                    break;
                }                        
                case "administrative_area_level_1": {
                    selectedState = component.long_name;
                    var autocompleteStateElement = document.getElementById('bill_state_id');
                    var autocompleteStateOptions = autocompleteStateElement.options;

                    for (var i = 0; i < autocompleteStateOptions.length; i++) {
                        if (autocompleteStateOptions[i].text.toLowerCase() === selectedState.toLowerCase()) {
                            autocompleteStateOptions[i].selected = true;
                            break;
                        }
                    }
                    break;
                }
            }
        }
        document.querySelector("#bill_suite").focus();
    }

    function fillInShipAddress() {
        const place = shipAddressAutocomplete.getPlace(); 

        for (const component of place.address_components) {
            const componentType = component.types[0];
            switch (componentType) {    
                case "postal_code": {
                    document.querySelector("#ship_zipcode").value = component.long_name;
                    break;
                }                
                case "locality": {
                    document.querySelector("#ship_city").value = component.long_name;
                    break;
                }                        
                case "administrative_area_level_1": {
                    selectedState = component.long_name;
                    var autocompleteStateElement = document.getElementById('ship_state_id');
                    var autocompleteStateOptions = autocompleteStateElement.options;

                    for (var i = 0; i < autocompleteStateOptions.length; i++) {
                        if (autocompleteStateOptions[i].text.toLowerCase() === selectedState.toLowerCase()) {
                            autocompleteStateOptions[i].selected = true;
                            break;
                        }
                    }
                    break;
                }
            }
        }
        document.querySelector("#ship_suite").focus();
    }
    window.initAutocomplete = initAutocomplete;    
</script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{ env('GOOGLE_MAP_API_KEY') }}&callback=initAutocomplete&libraries=places&v=weekly"></script>
@endsection
