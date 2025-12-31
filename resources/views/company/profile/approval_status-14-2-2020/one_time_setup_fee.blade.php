@if ($company_approval_status->one_time_setup_fee != 'not required')
<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->one_time_setup_fee) }}">

    @if ($company_approval_status->one_time_setup_fee == 'completed')
	    <a href="javascript:;" data-toggle="modal" data-target="#oneTimeSetupFeeModal">One Time Setup Fee</a>

	    @php
	    	$company_invoice_detail = \App\Models\CompanyInvoice::where([
	    		['company_id', $company_item->id],
	    		['invoice_type', 'One Time Setup Fee & Prescreen/Background Check Fees'],
	    		['status', 'paid']
	    	])->latest()->first();
	    @endphp
	    
	    <div class="modal fade" id="oneTimeSetupFeeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	    aria-hidden="true">
		    <div class="modal-dialog modal-md" role="document">
		        <div class="modal-content ">
		            <div class="modal-header text-center">
		                <h4 class="modal-title w-100 font-weight-bold text-left">One Time Setup Fee</h4>
		                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		                    <span aria-hidden="true">&times;</span>
		                </button>
		            </div>

		            <div class="modal-body text-center">
		            	<h4>One Time Setup Fee was paid on {{ (!is_null($company_invoice_detail)) ? $company_invoice_detail->invoice_date : '' }}!</h4>
		            	<div class="clearfix">&nbsp;</div>
		            	
		            	<h5>Thank You!</h5>

		            	<div class="clearfix">&nbsp;</div>

		            	<p class="text-danger">*This fee is refunded if not approved, but NON REFUNDABLE once approved.</p>
		            </div>
		        </div>
		    </div>
		</div>
    @else
    One Time Setup Fee
    @endif


    {!!
    $company_approval_status->showStatusIcon($company_approval_status->one_time_setup_fee)
    !!}
</li>
@endif