<!-- Worker Compensation insurance start -->
@if (!is_null($company_insurance) && $company_insurance->general_liability_insurance_and_worker_compensation_insurance == 'Yes' && $company_approval_status->worker_comsensation_insurance_file != 'not required')
<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->worker_comsensation_insurance_file) }}">

    @if ($company_approval_status->worker_comsensation_insurance_file == 'pending')
    <a href="javascript:;" data-toggle="modal" data-target="#pendingWorkerInsuranceModal">Worker Compensation Insurance</a>
    <div class="modal fade" id="pendingWorkerInsuranceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content ">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold text-left">Workers Compensation Insurance Certificate Holder</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body text-center">
                    <p class="font-15">We have not yet received a copy of your workers compensation insurance from your insurance provider showing Trust Patrick as a certificate holder.</p>
                    <p class="font-15">If you have not done so already, please fill out the forms necessary to send to your insurance agent/agents.</p>
                    <p class="font-15">Once your insurance company has provided us with the insurance document showing Trust Patrick as a certificate holder on your workers compensation insurance policy, we will upload this document into the system and mark it as completed.</p>
                    <p class="font-15">Workers Compensation Certificate Holder Request Form</p>

                    <div class="btn-group btn-group-solid">
                        <a href="{{ url('account/application/worker-compensation-insurance-view') }}" target="_blank" class="btn btn-info btn-sm">View Request Form</a>
                        <a href="{{ url('account/application/worker-compensation-insurance-download') }}" class="btn btn-primary btn-sm" download>Download Form</a>
                    </div>
                    <div class="clearfix">&nbsp;</div>

                    <a href="javascript:;" class="btn btn-primary btn-sm mark_as_completed_insurance" data-type="worker_compensation_insurance">I've done this!</a>
                    <div class="clearfix">&nbsp;</div>
                    <h5>Thank You!</h5>
                </div>
            </div>
        </div>
    </div>
    @elseif ($company_approval_status->worker_comsensation_insurance_file == 'in process')
    <a href="javascript:;" data-toggle="modal" data-target="#inProcessWorkerInsuranceModal">Worker Compensation Insurance</a>
    <div class="modal fade" id="inProcessWorkerInsuranceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content ">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold text-left">Workers Compensation Insurance Certificate Holder</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body text-center">
                    <p class="font-15">We have not yet received a copy of your workers compensation insurance from your insurance provider showing Trust Patrick as a certificate holder.</p>
                    <?php /* @if (!is_null($company_insurance->workers_compensation_insurance_mark_as_completed_date))
                    <p class="font-15">You marked this as completed on ({{ \App\Models\Custom::date_formats($company_insurance->workers_compensation_insurance_mark_as_completed_date, env('DB_DATE_FORMAT'), env('DATE_FORMAT')) }}).</p>
                    @endif */ ?>
                    <p class="font-15">If it has been several days since your request, please contact your insurance provider and followup up with them to keep the approval process moving swiftly.</p>
                    
                    <div class="clearfix">&nbsp;</div>
                    <h5>Thank You!</h5>
                </div>
            </div>
        </div>
    </div>
    @elseif ($company_approval_status->worker_comsensation_insurance_file == 'completed')
    <a href="javascript:;" data-toggle="modal" data-target="#completedWorkerInsuranceModal">Worker Compensation Insurance</a>
    <div class="modal fade" id="completedWorkerInsuranceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content ">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold text-left">Workers Compensation Insurance Certificate Holder</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body text-center">
                    <p class="font-15 font-bold">We've received your workers compensation documentation.</p>
                    <div class="clearfix">&nbsp;</div>
                    <h5>Thank You!</h5>
                </div>
            </div>
        </div>
    </div>
    @endif
    {!! $company_approval_status->showStatusIcon($company_approval_status->worker_comsensation_insurance_file) !!}
</li>
@endif
<!-- Worker Compensation insurance end -->