@if ($company_approval_status->customer_references != 'not required')
<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->customer_references) }}">

    @if ($company_approval_status->customer_references == 'completed')
    	<a href="javascript:;" data-toggle="modal" data-target="#customerReferenceModal">Customer References</a>
    	<div class="modal fade" id="customerReferenceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content ">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold text-left">Customer References</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body text-center">
                        <h4>We have received a copy of your Customer References Form.</h4>
                        <div class="clearfix">&nbsp;</div>
                        <h5>Thank You!</h5>
                    </div>
                </div>
            </div>
        </div>
    @else
    	Customer References
    @endif

    {!! $company_approval_status->showStatusIcon($company_approval_status->customer_references) !!}
</li>
@endif