@if ($company_approval_status->pre_screening_process != 'not required')
<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->pre_screening_process) }}">

    @if ($company_approval_status->pre_screening_process == 'completed')
    	<a href="javascript:;" data-toggle="modal" data-target="#preScreeningProcessModal">Pre Screening Process</a>

    	<div class="modal fade" id="preScreeningProcessModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	    aria-hidden="true">
		    <div class="modal-dialog modal-md" role="document">
		        <div class="modal-content ">
		            <div class="modal-header text-center">
		                <h4 class="modal-title w-100 font-weight-bold text-left">Pre Screening Process</h4>
		                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		                    <span aria-hidden="true">&times;</span>
		                </button>
		            </div>

		            <div class="modal-body text-center">
		            	<h4>Our Pre-Screening process has completed and cleared!</h4>
		            	<div class="clearfix">&nbsp;</div>
		            	<h5>Congratulations!</h5>
		            </div>
		        </div>
		    </div>
		</div>
    @else
    	Pre Screening Process
    @endif

    {!! $company_approval_status->showStatusIcon($company_approval_status->pre_screening_process) !!}
</li>
@endif