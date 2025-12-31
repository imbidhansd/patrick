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
        @if (isset($company_invoice) && count($company_invoice) > 0)
        <div class="row">
            @foreach ($company_invoice AS $company_invoice_item)
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header {{ $company_invoice_item->status == 'paid' ? 'bg-primary' : 'bg-warning' }}">
                        <h3 class="card-title {{ $company_invoice_item->status == 'paid' ? 'text-white' : '' }}  mb-0">Invoice: {{ $company_invoice_item->invoice_id }}</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group bs-ui-list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b>Invoice No.</b>
                                <span>{{ $company_invoice_item->invoice_id }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b>Payment Status: </b>
                                @if ($company_invoice_item->status == 'paid')
                                    <span class="badge badge-info">{{ ucfirst($company_invoice_item->status) }}</span>
                                @else
                                    <span class="badge badge-warning">{{ ucfirst($company_invoice_item->status) }}</span>
                                @endif
                            </li>
                            @if (!is_null($company_invoice_item->invoice_paid_date))
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b>Payment Date: </b>
                                <span>
                                    {{ $company_invoice_item->invoice_paid_date }}
                                </span>
                            </li>
                            @endif

                            @if ($company_invoice_item->status == 'pending')
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b>Amount Due: </b>
                                <span class="badge badge-danger">{{ '$'.number_format($company_invoice_item->final_amount, 2) }}</span>
                            </li>
                            @endif

                            @if ($company_invoice_item->status == 'paid' || $company_invoice_item->status == 'waiting')
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b>Amount Paid: </b>
                                <span>{{ '$'.number_format($company_invoice_item->final_amount, 2) }}</span>
                            </li>
                            @endif

                            @if (!is_null($company_invoice_item->payment_type))
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b>Payment Type: </b>
                                <span>{{ ucfirst(str_replace('_', ' ', $company_invoice_item->payment_type)) }}</span>
                            </li>
                            @endif

                            @if (!is_null($company_invoice_item->transaction_id))
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b>Transaction ID: </b>
                                <span>{{ $company_invoice_item->transaction_id }}</span>
                            </li>
                            @endif

                            @if (!is_null($company_invoice_item->subscription_id))
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b>Subscription ID: </b>
                                <span>{{ $company_invoice_item->subscription_id }}</span>
                            </li>
                            @endif
                        </ul>
                    </div>

                    <div class="card-footer text-right">
                        <div class="btn-group btn-group-solid">
                            <a href="{{ url('billing/view-invoice', ['invoice_id' => $company_invoice_item->invoice_id]) }}" class="btn btn-info btn-sm">View Invoice</a>

                            @if ($company_invoice_item->status == 'paid' || $company_invoice_item->status == 'waiting')
                                <a href="{{ url('billing/download-invoice', ['invoice_id' => $company_invoice_item->invoice_id]) }}" class="btn btn-success btn-sm">Download Invoice</a>
                            @else
                                @if (is_null($company_invoice_item->payment_type))
                                <a href="{{ url('billing/invoice-payment', ['invoice_id' => $company_invoice_item->invoice_id]) }}" class="btn btn-dark btn-sm">Pay Now</a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if ($loop->iteration%2 == 0)
            </div><div class="row">
            @endif

            @endforeach
        </div>
        @else
        No Invoice found.
        @endif


        <div class="float-left">
            {!! $company_invoice->render() !!}
        </div>
    </div>
    @include('company.profile._company_profile_sidebar')
</div>


{!! Form::open(['url' => 'billing/cancel-subscription', 'id' => 'cancel_subscription_form']) !!}
{!! Form::hidden('company_id', $company_item->id) !!}
{!! Form::close() !!}

@endsection

@section ('page_js')
@include('company.profile._js')
<script type="text/javascript">
    $(function (){
        $(".cancel_subscription").on("click", function (){
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#ff0000",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, Cancel it!"
            }).then(function (t) {
                if (typeof t.value !== 'undefined'){
                    $("#cancel_subscription_form").submit();
                }
            });
        });
    });
</script>
@endsection