@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')

@include('flash::message')

<div class="card-box">
    <h1 class="text-center">{{ $admin_page_title }}</h1>
    <div class="clearfix">&nbsp;</div>

    @include('admin.includes.formErrors')
    {!! Form::open(['url' => url('billing/invoice-payment'), 'class' => 'module_form ']) !!}

    {!! Form::hidden('company_id', $companyObj->id, ['required' => true]) !!}
    {!! Form::hidden('invoice_id', $company_invoice->invoice_id, ['required' => true]) !!}
    
    <div class="row">
        @if ($companyObj->membership_level->pay_by_check == 'no')
        <div class="col-md-3">&nbsp;</div>
        @endif

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary py-3 text-white">
                    <h5 class="card-title mb-0 text-white">Summary</h5>
                </div>

                <div id="summary" class="collapse show">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" width="100%" border="0">
                                <tr>
                                    <td>{{ $company_invoice->invoice_for}}</td>
                                    <td>${{ number_format($company_invoice->final_amount, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @if ($companyObj->membership_level->pay_by_check == 'yes')
        <div class="col-md-6">
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
                            <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-money-check-alt"></i> &nbsp; Pay Now</button>
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary py-3 text-white">
                    <h5 class="card-title mb-0 text-white">Address Details</h5>
                </div>

                <div id="address_details" class="collapse show">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Shipping Address</h5>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Company Name</label>
                                            {!! Form::text('ship[company_name]', $company_information_obj->legal_company_name, ['class' => 'form-control', 'required' => true]) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            {!! Form::text('ship[first_name]', $company_user_obj->first_name, ['class' => 'form-control', 'required' => true]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            {!! Form::text('ship[last_name]', $company_user_obj->last_name, ['class' => 'form-control', 'required' => true]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mailing Address</label>
                                            {!! Form::text('ship[mailing_address]', $company_information_obj->mailing_address, ['class' => 'form-control', 'required' => true]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Suite</label>
                                            {!! Form::text('ship[suite]', $company_information_obj->suite, ['class' => 'form-control', 'required' => true]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>City</label>
                                            {!! Form::text('ship[city]', $company_information_obj->city, ['class' => 'form-control', 'required' => true]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>State</label>
                                            {!! Form::select('ship[state_id]', $states, $company_information_obj->state_id, ['class' => 'custom-select', "placeholder" => "State", 'required' => true]) !!}
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
                                            {!! Form::text('ship[zipcode]', $company_information_obj->zipcode, ['class' => 'form-control', 'required' => true]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phone Number</label>
                                            {!! Form::text('ship[phone]', $company_information_obj->main_company_telephone, ['class' => 'form-control', 'required' => true , 'maxlength' => 255, 'data-toggle' =>
                                            'input-mask',
                                            'data-mask-format' => '(000) 000-0000']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5>Billing Address</h5>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Company Name</label>
                                            {!! Form::text('bill[company_name]', $company_information_obj->legal_company_name, ['class' => 'form-control', 'required' => true]) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            {!! Form::text('bill[first_name]', $company_user_obj->first_name, ['class' => 'form-control', 'required' => true]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            {!! Form::text('bill[last_name]', $company_user_obj->last_name, ['class' => 'form-control', 'required' => true]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mailing Address</label>
                                            {!! Form::text('bill[mailing_address]', $company_information_obj->mailing_address, ['class' => 'form-control', 'required' => true]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Suite</label>
                                            {!! Form::text('bill[suite]', $company_information_obj->suite, ['class' => 'form-control', 'required' => true]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>City</label>
                                            {!! Form::text('bill[city]', $company_information_obj->city, ['class' => 'form-control', 'required' => true]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>State</label>
                                            {!! Form::select('bill[state_id]', $states, $company_information_obj->state_id, ['class' => 'custom-select', "placeholder" => "State", 'required' => true]) !!}
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
                                            {!! Form::text('bill[zipcode]', $company_information_obj->zipcode, ['class' => 'form-control', 'required' => true]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phone Number</label>
                                            {!! Form::text('bill[phone]', $company_information_obj->main_company_telephone, ['class' => 'form-control', 'required' => true , 'maxlength' => 255, 'data-toggle' =>
                                            'input-mask',
                                            'data-mask-format' => '(000) 000-0000']) !!}
                                        </div>
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
        <div class="col-md-2">&nbsp;</div>
        <div class="col-md-8">
            <div class="card" id="credit_card_payment_detail" style="{{ (($companyObj->membership_level->pay_by_check == 'yes') ? 'display:none;' : '') }}">
                <div class="card-header bg-primary py-3 text-white">
                    <h5 class="card-title mb-0 text-white">Payment Detail</h5>
                </div>

                <div id="payment_detail" class="collapse show">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Card Holder Name:*</label>
                                    {!! Form::text('card_name', null, ['class' => 'form-control', 'id' => 'card_name', 'placeholder' => 'Card Holder Name', 'required' => true]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('Card Number:*') !!}
                                    {!! Form::text('card_number', null, ['class' => 'form-control', 'placeholder' => 'Enter Card Number', 'maxlength' => 16, 'data-parsley-type' => 'integer', 'id' => 'card', 'required' => true, 'data-toggle' => 'input-mask', 'data-mask-format' => '0000000000000000']) !!}
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    {!! Form::label('Exp Month (MM/YYYY):*') !!}

                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::select('exp_month', $exp_month_list , null, ['class' =>
                                            'form-control custom-select', 'placeholder' => 'Enter Exp Month', 'id' => 'expiration-month', 'required' => true]) !!}
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::select('exp_year',$exp_year_list, null, ['class' => 'form-control custom-select', 'placeholder' => 'Select Exp Year', 'id' => 'expiration-year', 'required' => true]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('CVC') !!}
                                    {!! Form::tel('cvv', null, ['class' => 'form-control', 'data-parsley-maxlength' => 4, 'placeholder' => 'Enter CVC', 'data-parsley-type' => 'integer', 'id' => 'cvc', 'required' => true, 'data-toggle' => 'input-mask', 'data-mask-format' => '000']) !!}
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Terms & Conditions*</label>

                                    <div class="checkbox checkbox-primary checkbox-circle">
                                        <input type="checkbox" id="terms" name="terms" value="term_yes"
                                            required="required" />
                                        <label for="terms">
                                            I have read and agree to the
                                            <a href="#">Background Check/Credit Check Requirement</a>
                                        </label>
                                    </div>

                                    <div class="checkbox checkbox-primary checkbox-circle">
                                        <input type="checkbox" id="terms1" name="terms1" value="term1_yes"
                                            required="required" />
                                        <label for="terms1">
                                            I have read and agree to the
                                            <a href="#">Term of Use</a>
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
                            <button type="submit" class="btn btn-primary btn-block">
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
    
@endsection

@section ('page_js')
@include('company.invoices._checkout_payment_js')
@endsection