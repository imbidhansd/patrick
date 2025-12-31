@if ($company_approval_status->insurance_documents != 'not required')
<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->insurance_documents) }}">

    @if ($company_approval_status->insurance_documents == 'completed')
    	<a href="javascript:;" data-toggle="modal" data-target="#insuranceDocumentsModal">Insurance Documents</a>
    	<div class="modal fade" id="insuranceDocumentsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content ">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold text-left">Insurance Documents</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body text-center">
                        <h4>We’ve received your insurance documents!</h4>
                        <div class="clearfix">&nbsp;</div>
                        <h5>Thank You!</h5>
                    </div>
                </div>
            </div>
        </div>
    @else
    Insurance Documents
    @endif
    
    {!! $company_approval_status->showStatusIcon($company_approval_status->insurance_documents) !!}
</li>
@endif