@if ($company_approval_status->background_check_process != 'not required')
<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->background_check_process) }}">

    @if ($company_approval_status->background_check_process == 'pending')
    	<a href="javascript:;" data-toggle="modal" data-target="#backgroundCheckProcessModal">Background Check Process</a>
    @elseif ($company_approval_status->background_check_process == 'completed')
		<a href="javascript:;" data-toggle="modal" data-target="#backgroundCheckProcessModal">Background Check Process</a>    
    @endif
    
    {!! $company_approval_status->showStatusIcon($company_approval_status->background_check_process) !!}
</li>
@endif

@if ($company_approval_status->background_check_process == 'pending' || $company_approval_status->background_check_process == 'completed')
<div class="modal fade" id="backgroundCheckProcessModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Background Check Process</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
            	@if ($company_approval_status->background_check_process == 'pending')
                <h4>Your background check has not yet been submitted. Please submit your background check as soon as possible to keep the approval process running smoothly.</h4>
                @elseif ($company_approval_status->background_check_process == 'completed')
                <h4>Your background check has been submitted.</h4>
                @endif
                <div class="clearfix">&nbsp;</div>
                <h5>Thank You!</h5>
            </div>
        </div>
    </div>
</div>
@endif