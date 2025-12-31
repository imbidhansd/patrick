@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')

@include('flash::message')

<div class="card-box">
    <h1 class="text-center">{{ $admin_page_title }}</h1>
    <div class="clearfix">&nbsp;</div>

    @include('admin.includes.formErrors')
    {!! Form::open(['url' => url('billing/invoice-payment'), 'class' => 'module_form', 'id' => 'checkout_form']) !!}

    {!! Form::hidden('company_id', $companyObj->id, ['required' => true]) !!}
    {!! Form::hidden('invoice_id', $company_invoice->invoice_id, ['required' => true]) !!}

    <div class="row">
        <?php /* <div class="col-md-3">&nbsp;</div> */ ?>
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary py-3 text-white">
                    <h5 class="card-title mb-0 text-white">Summary</h5>
                </div>

                <div id="summary" class="collapse show">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0" width="100%" border="0">
                                <?php /* <tr>
                                    <th class="bt-none" colspan="2">Charges Upon Approval</th>
                                </tr> */ ?>
                                
                                @php 
                                $grand_total = 0;                               
                                @endphp
                                @foreach($company_invoice->company_invoice_item AS $company_invoice_item)
                                @if ($company_invoice_item->total != 0)
                                @php $grand_total += $company_invoice_item->total; @endphp
                                <tr>
                                    <td class="text-right bt-none">{{ $company_invoice_item->title }}</td>
                                    <td class="text-right bt-none" width="30%">${{ number_format($company_invoice_item->total, 2) }}</td>
                                </tr>
                                @endif
                                @endforeach
                                
                                <tr>
                                    <?php /* <th>Total Charges Upon Approval</th> */ ?>
                                    <th>Total Charges</th>
                                    <th class="text-right">${{ number_format($grand_total, 2) }}</th>
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
                        <h5>Billing Address</h5>

                        <?php
                        /*
                         * $company_information_obj->legal_company_name
                         * $company_user_obj->first_name
                         * $company_user_obj->last_name
                         * $company_information_obj->mailing_address
                         * $company_information_obj->suite
                         * $company_information_obj->city
                         * $company_information_obj->state_id
                         * $company_information_obj->zipcode
                         * $company_information_obj->main_company_telephone
                         */
                        ?>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Company Name</label>
                                    {!! Form::text('bill[company_name]', $last_invoice_item->bill_address->company_name ?? '', ['class' => 'form-control', 'id' => 'bill_company_name', 'required' => true]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>First Name</label>
                                    {!! Form::text('bill[first_name]', $last_invoice_item->bill_address->first_name ?? '', ['class' => 'form-control', 'id' => 'bill_first_name', 'required' => true]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Last Name</label>
                                    {!! Form::text('bill[last_name]', $last_invoice_item->bill_address->last_name ?? '', ['class' => 'form-control', 'id' => 'bill_last_name', 'required' => true]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mailing Address</label>
                                    {!! Form::text('bill[mailing_address]', $last_invoice_item->bill_address->mailing_address ?? '', ['class' => 'form-control', 'id' => 'bill_mailing_address', 'maxlength' => 60, 'required' => true]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Suite</label>
                                    {!! Form::text('bill[suite]', $last_invoice_item->bill_address->suite ?? '', ['class' => 'form-control', 'id' => 'bill_suite', 'required' => false]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>City</label>
                                    {!! Form::text('bill[city]', $last_invoice_item->bill_address->city ?? '', ['class' => 'form-control', 'id' => 'bill_city', 'required' => true]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>State</label>
                                    {!! Form::select('bill[state_id]', $states, $last_invoice_item->bill_address->state_id ?? '', ['class' => 'form-control custom-select', 'id' => 'bill_state_id', "placeholder" => "State", 'required' => true]) !!}
                                </div>
                            </div>
                            <?php /* <div class="col-md-6">
                              <div class="form-group">
                              <label>County</label>
                              {!! Form::text('bill[county]', $company_information_obj->county, ['class' => 'form-control', 'required' => true]) !!}
                              </div>
                              </div> */ ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Zipcode</label>
                                    {!! Form::text('bill[zipcode]', $last_invoice_item->bill_address->zipcode ?? '', ['class' => 'form-control', 'id' => 'bill_zipcode', 'data-toggle' => 'input-mask', 'data-mask-format' => '00000', 'required' => true]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    {!! Form::text('bill[phone]', $last_invoice_item->bill_address->phone ?? '', ['class' => 'form-control', 'id' => 'bill_phone', 'required' => true , 'maxlength' => 255, 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000']) !!}
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
                                        {!! Form::text('ship[company_name]', $last_invoice_item->ship_address->company_name ?? '', ['class' => 'form-control', 'id' => 'ship_company_name', 'required' => true]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        {!! Form::text('ship[first_name]', $last_invoice_item->ship_address->first_name ?? '', ['class' => 'form-control', 'id' => 'ship_first_name', 'required' => true]) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        {!! Form::text('ship[last_name]', $last_invoice_item->ship_address->last_name ?? '', ['class' => 'form-control', 'id' => 'ship_last_name', 'required' => true]) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mailing Address</label>
                                        {!! Form::text('ship[mailing_address]', $last_invoice_item->ship_address->mailing_address ?? '', ['class' => 'form-control', 'id' => 'ship_mailing_address', 'maxlength' => 60, 'required' => true]) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Suite</label>
                                        {!! Form::text('ship[suite]', $last_invoice_item->ship_address->suite ?? '', ['class' => 'form-control', 'id' => 'ship_suite', 'required' => false]) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>City</label>
                                        {!! Form::text('ship[city]', $last_invoice_item->ship_address->city ?? '', ['class' => 'form-control', 'id' => 'ship_city', 'required' => true]) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>State</label>
                                        {!! Form::select('ship[state_id]', $states, $last_invoice_item->ship_address->state_id ?? '', ['class' => 'form-control custom-select', 'id' => 'ship_state_id', "placeholder" => "State", 'required' => true]) !!}
                                    </div>
                                </div>
                                <?php /* <div class="col-md-6">
                                  <div class="form-group">
                                  <label>County</label>
                                  {!! Form::text('ship[county]', $company_information_obj->county, ['class' => 'form-control', 'required' => true]) !!}
                                  </div>
                                  </div> */ ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Zipcode</label>
                                        {!! Form::text('ship[zipcode]', $last_invoice_item->ship_address->zipcode ?? '', ['class' => 'form-control', 'id' => 'ship_zipcode', 'data-toggle' => 'input-mask', 'data-mask-format' => '00000', 'required' => true]) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        {!! Form::text('ship[phone]', $last_invoice_item->ship_address->phone ?? '', ['class' => 'form-control', 'id' => 'ship_phone', 'required' => true , 'maxlength' => 255, 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000']) !!}
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
        @if ($companyObj->membership_level->pay_by_check == 'yes')
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary py-3 text-white">
                    <h5 class="card-title mb-0 text-white">Choose Payment Type</h5>
                </div>

                <div id="payment_type" class="collapse show">
                    <div class="card-body">
                        <div class="radio radio-primary radio-circle">
                            <input type="radio" class="payment_option" id="credit_card" name="payment_option"
                                   value="credit_card" />
                            <label for="credit_card">Pay By Credit Card</label>
                        </div>
                        <div class="radio radio-dark radio-circle">
                            <input type="radio" class="payment_option" id="check" name="payment_option" value="check" />
                            <label for="check">Pay By Check</label>
                        </div>

                        <div class="clearfix">&nbsp;</div>
                        <div class="text-right" id="check_payment_detail" style="display: none;">
                            <button type="submit" class="btn btn-primary btn-block checkout_btn"><i class="fas fa-money-check-alt"></i> &nbsp; Pay Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        {!! Form::hidden('payment_option', 'credit_card') !!}
        @endif
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card" id="credit_card_payment_detail" style="{{ (($companyObj->membership_level->pay_by_check == 'yes') ? 'display:none;' : '') }}">
                <div class="card-header bg-primary py-3 text-white">
                    <h5 class="card-title mb-0 text-white">Payment Detail</h5>
                </div>

                <div id="payment_detail" class="collapse show">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Terms & Conditions*</label>

                                    <?php /* <div class="checkbox checkbox-primary checkbox-circle">
                                        <input type="checkbox" id="terms" name="terms" value="term_yes"
                                               required="required" />
                                        <label for="terms">
                                            I have read and agree to the
                                            <a href="#">Background Check/Credit Check Requirement</a>
                                        </label>
                                    </div> */ ?>

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
                                    <td>{{ $company_invoice->invoice_for}}</td>
                                    <td class="text-right">
                                        <strong>${{ number_format($company_invoice->final_amount, 2) }}</strong>
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
@endsection