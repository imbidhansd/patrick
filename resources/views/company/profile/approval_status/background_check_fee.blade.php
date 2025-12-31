@if ($company_approval_status->background_check_pre_screen_fees != 'not required')
<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->background_check_pre_screen_fees) }}">

    @if ($company_approval_status->background_check_pre_screen_fees == 'completed')
    <a href="javascript:;" data-toggle="modal" data-target="#backgroundCheckFeeModal">Background/Credit Check/Pre Screen Fees</a>

    @php
    $company_invoice_detail = \App\Models\CompanyInvoice::where([
    ['company_id', $company_item->id],
    ['invoice_type', 'One Time Setup Fee & Prescreen/Background Check Fees'],
    ['status', 'paid']
    ])->latest()->first();
    @endphp

    <div class="modal fade" id="backgroundCheckFeeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content ">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold text-left">Background/Credit Check/Pre Screen Fees</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body text-center">
                    <p class="font-15 font-bold">Background/Credit Check/Pre Screen Fees were paid on {{ (!is_null($company_invoice_detail)) ? $company_invoice_detail->invoice_date : '' }}!</p>
                    <div class="clearfix">&nbsp;</div>

                    <h5>Thank You!</h5>

                    <div class="clearfix">&nbsp;</div>

                    <p class="text-danger">*This Fee is NON REFUNDABLE.</p>
                </div>
            </div>
        </div>
    </div>
    @else
    Background/Credit Check/Pre Screen Fees
    @endif

    {!!
    $company_approval_status->showStatusIcon($company_approval_status->background_check_pre_screen_fees)
    !!}
</li>
@endif