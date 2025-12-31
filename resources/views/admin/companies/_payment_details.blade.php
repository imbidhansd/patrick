@if(isset($company_invoices) && count($company_invoices) > 0)
<div class="row">
    @foreach ($company_invoices AS $company_invoice_item)
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

                    @if ($company_invoice_item->status == 'paid')
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
                    
                    @if ($company_invoice_item->payment_type == 'check' && $company_invoice_item->status == 'pending')
                    {!! Form::hidden('invoice_id', $company_invoice_item->invoice_id, ['class' => 'invoice_id']) !!}
                    {!! Form::hidden('invoice_amount', $company_invoice_item->final_amount, ['class' => 'invoice_amount']) !!}
                    <a href="javascript:;" data-toggle="modal" data-target="#markInvoicePaid" title="Mark As Paid" class="btn btn-primary btn-xs mark_invoice_paid"><i class="fas fa-money-check-alt"></i></a>
                    @endif

                    <a href="{{ url('admin/companies/view-invoice', ['invoice_id' => $company_invoice_item->invoice_id]) }}" title="View Invoice" class="btn btn-orange btn-xs"><i class="fa fa-eye"></i></a>

                    <a href="{{ url('admin/companies/download-invoice', ['invoice_id' => $company_invoice_item->invoice_id]) }}" title="Download Invoice" class="btn btn-info btn-xs"><i class="fas fa-download"></i></a>

                    <a title="Delete Invoice" href="javascript:;" class="btn btn-danger delete_invoice_btn btn-xs" data-id="{{ $company_invoice_item->id }}"><i class="fa fa-trash"></i></a>
                </div>
            </div>
        </div>
    </div>

    @if ($loop->iteration%2 == 0)
    </div><div class="row">
    @endif
    @endforeach
</div>
@endif

<div class="float-left mt-3">
    {!! $company_invoices->appends(['company_invoices' => $company_invoices->currentPage()])->render() !!}
</div>


<div class="modal fade" id="markInvoicePaid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">
                    Mark Invoice As Paid
                    <span id="invoice_id_display">#</span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('admin/companies/mark-invoice-paid'), 'class' => 'module_form ']) !!}
            {!! Form::hidden('invoice_id', null, ['id' => 'invoice_id']) !!}
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('Total Amount') !!}
                    {!! Form::text('total_amount', null, ['class' => 'form-control', 'id' => 'total_amount', 'readonly' => true]) !!}
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

{!! Form::open(['url' => url('admin/companies/delete-invoice'), 'class' => 'module_form', 'id' => 'invoice_delete_form']) !!}
{!! Form::hidden('invoice_id', null, ['id' => 'invoice_id_h']) !!}
{!! Form::close() !!}



@push('_edit_company_profile_js')
<link href="{{ asset('themes/admin/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('themes/admin/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

<script type="text/javascript">
    $(function (){
        $(".mark_invoice_paid").on("click", function (){
            var total_amount = $(this).parents(".card-footer").find(".invoice_amount").val(); 
            var invoice_id = $(this).parents(".card-footer").find(".invoice_id").val();

            $("#markInvoicePaid #invoice_id").val(invoice_id);
            $("#markInvoicePaid #invoice_id_display").text('#'+invoice_id);
            
            $("#markInvoicePaid #total_amount").val(total_amount.trim());
        });

        $('.delete_invoice_btn').on("click", function () {
            $('#invoice_delete_form #invoice_id_h').val($(this).data('id'));

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
                    $('#invoice_delete_form').submit();
                }
            })
            return false;
        });
    });
</script>
@endpush