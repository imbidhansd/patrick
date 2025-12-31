@if ($company_approval_status->online_application != 'not required')
<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->online_application) }}">

    @if ($company_approval_status->online_application == 'pending')
    	<a href="javascript:;" data-toggle="modal" data-target="#onlineApplocationModal">Online Application</a>
    @elseif ($company_approval_status->online_application == 'completed')
		<a href="javascript:;" data-toggle="modal" data-target="#onlineApplocationModal">Online Application</a>    
    @endif
    
    {!! $company_approval_status->showStatusIcon($company_approval_status->online_application) !!}
</li>
@endif


@if ($company_approval_status->online_application == 'pending' || $company_approval_status->online_application == 'completed')
<div class="modal fade" id="onlineApplocationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Online Application</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
            	@if ($company_approval_status->online_application == 'pending')
                <h4>Your Online Application is not Submitted! Please Submit Your Application </h4>
                @elseif ($company_approval_status->online_application == 'completed')
                <h4>Your Online Application has been Submitted!</h4>
                @endif
                <div class="clearfix">&nbsp;</div>
                <h5>Thank You!</h5>
            </div>
        </div>
    </div>
</div>
@endif