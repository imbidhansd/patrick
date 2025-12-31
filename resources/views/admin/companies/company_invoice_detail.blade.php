@extends('admin.layout')
@section('title', $admin_page_title)

@section ('content')

@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => $module_urls['list'],
$admin_page_title => '']])
@include('flash::message')

<div class="card-box {{ ($company_invoice->status == 'paid') ? 'paid-invoice' : ''}}">
    @include('admin.includes.formErrors')

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
                <span class="badge badge-success">{{ ucfirst($company_invoice->status) }}</span>
                @else
                <span class="badge badge-info">{{ ucfirst($company_invoice->status) }}</span>
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
                        <tr>
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
                        <tr>
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
            <?php /* <small>Please make all checks payable to TrustPatrick.com</small>*/ ?>
        </div>
        <div class="col-md-6 text-right">
            <h3>Total: {{ '$'.number_format($final_total, 2) }}</h3>
        </div>
    </div>


    <hr />
    <div class="text-right">
        @if ($company_invoice->payment_type == 'check' && $company_invoice->status == 'pending')
        <a href="javascript:;" data-toggle="modal" data-target="#markInvoicePaid" title="Mark As Paid" class="btn btn-primary btn-sm mark_invoice_paid"><i class="fas fa-money-check-alt"></i> &nbsp; Mark As Paid</a>
        @endif
        
        <a href="{{ url('admin/companies/download-invoice', ['invoice_id' => $company_invoice->invoice_id]) }}" title="Download Invoice" class="btn btn-success btn-sm"><i class="fas fa-download"></i> &nbsp; Download</a>
    </div>

</div>


<div class="modal fade" id="markInvoicePaid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">
                    Mark Invoice As Paid
                    <span id="invoice_id_display">#{{ $company_invoice->invoice_id}}</span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('admin/companies/mark-invoice-paid'), 'class' => 'module_form ']) !!}
            {!! Form::hidden('invoice_id', $company_invoice->invoice_id) !!}
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('Total Amount') !!}
                    {!! Form::text('total_amount', $final_total, ['class' => 'form-control', 'id' => 'total_amount', 'readonly' => true]) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('Paid Date') !!}
                    <div class="input-group">
                        {!! Form::text('invoice_paid_date', null, ['class' => 'form-control date_field', 'autocomplete' => 'off', 'placeholder' => 'MM/DD/YYYY', 'required' => true]) !!}
                        <div class="input-group-append">
                            <span class="input-group-text bg-primary text-white b-0"><i class="mdi mdi-calendar"></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('Note') !!}
                    {!! Form::textarea('note', null, ['class' => 'form-control', 'placeholder' => 'Note', 'required' => true]) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light">Save changes</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop

@section('page_js')
<link href="{{ asset('themes/admin/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('themes/admin/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
@stop
