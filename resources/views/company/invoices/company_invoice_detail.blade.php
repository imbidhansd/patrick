<?php
$admin_page_title = 'Billing';
?>
@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="row">
    <div class="col-lg-9 col-md-8 col-sm-12">
        <div class="card">

            <div class="card-header bg-primary">
                <div class="card-widgets">
                    <a href="{{ url('billing/download-invoice', ['invoice_id' => $company_invoice->invoice_id]) }}" class="btn btn-success btn-sm text-white"><i class="fas fa-download"></i> Download</a>
                </div>
                <h3 class="card-title text-white mb-0">Invoice Detail</h3>
            </div>
            <div class="card-body {{ ($company_invoice->status == 'paid') ? 'paid-invoice' : ''}}">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="mt-0">
                            <img src="{{ asset('/images/header-logo.png') }}" alt="{{ env('SITE_TITLE') }}" height="50" class="mr-1">
                        </h3>
                    </div>

                    <div class="col-md-6 text-right">
                        <h5 class="mt-0">
                            Invoice # <br>
                            <strong>{{ $company_invoice->invoice_id }}</strong>
                        </h5>
                    </div>
                </div>

                <hr />

                <div class="row">
                    <div class="col-md-12 text-center">
                        <h4><strong>Invoice For:</strong> {{ $company_invoice->invoice_for }}</h4>
                    </div>
                </div>

                <hr />

                <div class="row">
                    <div class="col-md-8 mt-3">
                        <div class="row">
                            <div class="col-md-6">
                                @if (!is_null($company_invoice->ship_address))
                                <address>
                                    <strong>Shipping Address: {{ $company_invoice->ship_address->company_name }}</strong> <br />
                                    {{ $company_invoice->ship_address->first_name }} {{ $company_invoice->ship_address->last_name }} <br />
                                    {{ $company_invoice->ship_address->mailing_address }} <br />
                                    {{ $company_invoice->ship_address->city }}, {{ $company_invoice->ship_address->state->name }} - {{ $company_invoice->ship_address->zipcode }}<br/>
                                    {{ $company_invoice->ship_address->phone }}
                                </address>
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if (!is_null($company_invoice->bill_address))
                                <address>
                                    <strong>Billing Address: {{ $company_invoice->bill_address->company_name }}</strong> <br />
                                    {{ $company_invoice->bill_address->first_name }} {{ $company_invoice->bill_address->last_name }} <br />
                                    {{ $company_invoice->bill_address->mailing_address }} <br />
                                    {{ $company_invoice->bill_address->city }}, {{ $company_invoice->bill_address->state->name }} - {{ $company_invoice->bill_address->zipcode }}<br/>
                                    {{ $company_invoice->bill_address->phone }}
                                </address>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mt-3 text-right">
                        <p class="mb-1">
                            <strong>Invoice Date: </strong>
                            {{ $company_invoice->invoice_date }}
                        </p>
                        <p class="mb-1">
                            <strong>Invoice: </strong>
                            #{{ $company_invoice->invoice_id }}
                        </p>
                        <p class="mb-1">
                            <strong>Invoice Status: </strong>
                            @if ($company_invoice->status == 'paid')
                            <span class="badge badge-info">{{ ucfirst($company_invoice->status) }}</span>
                            @else
                            <span class="badge badge-warning">{{ ucfirst($company_invoice->status) }}</span>
                            @endif
                        </p>
                        @if ($company_invoice->transaction_id != '')
                        <p class="mb-1">
                            <strong>Trasaction ID: </strong>
                            {{ $company_invoice->transaction_id }}
                        </p>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table mt-4">
                                <thead>
                                    <tr class="xs-hidden">
                                        <th class="table_head">#</th>
                                        <th class="table_head">Item</th>
                                        <th class="table_head">Description</th>
                                        <th class="table_head text-right">Quantity</th>
                                        <th class="table_head text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $final_total = 0; $i = 1; @endphp
                                    @forelse($company_invoice->company_invoice_item AS $company_invoice_item)
                                    @php $final_total += $company_invoice_item->total; @endphp
                                    <tr class="xs-hidden">
                                        <td class="text-left valigntop">{{ $i }}</td>
                                        <td class="text-left valigntop">{{ $company_invoice_item->title }}</td>
                                        <td class="text-left">{!! $company_invoice_item->description !!}</td>
                                        @if ($company_invoice->company->membership_level->charge_type == 'ppl_price' && is_null($company_invoice_item->total))
                                            @if ($company_invoice->invoice_type == 'PPL Lead Invoice')
                                            <td class="text-right valigntop">{{ $company_invoice_item->qty }}</td>
                                            <td class="text-right valigntop">$0.00</td>
                                            @else
                                            <td class="text-right valigntop" colspan="2">Pay Per Lead Listing <br /> (Monthly Ongoing)</td>
                                            @endif
                                        @else
                                        <td class="text-right valigntop">{{ $company_invoice_item->qty }}</td>
                                        <td class="text-right valigntop">${{ number_format($company_invoice_item->total, 2) }}</td>
                                        @endif
                                    </tr>
                                    
                                    <tr class="xs-visible">
                                        <td>
                                            <b>Item: </b> {{ $company_invoice_item->title }}
                                            <br />
                                            <b>Description: </b>
                                            {!! $company_invoice_item->description !!}
                                            
                                            <br />
                                            <b>Quantity: </b>
                                            @if ($company_invoice->company->membership_level->charge_type == 'ppl_price' && is_null($company_invoice_item->total))
                                            Pay Per Lead Listing <br /> (Monthly Ongoing)
                                            @else
                                            {{ $company_invoice_item->qty }}
                                            @endif
                                            
                                            <br />
                                            <b>Amount: </b> ${{ number_format($company_invoice_item->total, 2) }}
                                        </td>
                                    </tr>

                                    @php $i++; @endphp
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="small text-dark">Thank you for your business!</h5>
                        <?php /* <small>Please make all checks payable to TrustPatrick.com</small> */ ?>
                    </div>
                    <div class="col-md-6 text-right">
                        <h3>Total: {{ '$'.number_format($final_total, 2) }}</h3>
                    </div>
                </div>

                <?php /* @if (is_null($company_invoice->payment_type) && $company_invoice->status == 'pending' && $company_item->status == 'Approved') */ ?>
                @if (is_null($company_invoice->payment_type) && $company_invoice->status == 'pending')
                <hr />
                <div class="text-right">
                    <a href="{{ url('billing/invoice-payment', ['invoice_id' => $company_invoice->invoice_id]) }}" class="btn btn-primary btn-sm"><i class="fas fa-money-check-alt"></i> &nbsp; Pay Now</a>
                </div>
                @endif
            </div>
        </div>
    </div>

    @include('company.profile._company_profile_sidebar')
</div>
@endsection

@section ('page_js')
@include('company.profile._js')
@endsection
