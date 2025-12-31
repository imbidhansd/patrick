@if ($company_approval_status->pre_screening_process != 'not required')
<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->pre_screening_process) }}">
    <a href="javascript:;" data-toggle="modal" data-target="#preScreeningProcessModal">Pre Screening Process</a>
    
    @php
        $arrCount = 0;
        $processCount = 0;
        
        //'pre_screening_process',
        $statusColumnsArr = [
            'background_check_pre_screen_fees',
            'one_time_setup_fee',
            'background_check_submittal',
            'background_check_process',
            'online_application',
            'registered_legally_to_state',
            'proof_of_ownership',
            'state_licensing',
            'country_licensing',
            'city_licensing',
            'work_agreements_warranty',
            'subcontractor_agreement',
            'general_liablity_insurance_file',
            'worker_comsensation_insurance_file',
            'customer_references',
            'company_logo',
            'company_bio',
            'credit_check_report_status',
        ];
        
        
        $status = $company_approval_status->pre_screening_process;
        if ($company_item->status == 'Pending Approval'){
            foreach ($statusColumnsArr as $column_item) {
                if ($company_approval_status->$column_item != 'not required'){
                    $arrCount++;
                }
                
                if ($company_approval_status->$column_item == 'in process' || $company_approval_status->$column_item == 'completed'){
                    $processCount++;
                }
            }
            
            if ($processCount == $arrCount){
                $status = "in process";
            } else {
                $status = "pending";
            }
        }
    @endphp
    {!! $company_approval_status->showStatusIcon($status) !!}
</li>

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
                @if ($company_approval_status->pre_screening_process == 'pending' || $company_approval_status->pre_screening_process == 'in process')
                <p class="font-15">The pre-screening process has not been completed. Once all background checks have been submitted and all incomplete tasks have been completed, we will complete the pre-screening process.
                    <br/>Please be sure and upload any files indicating "Not Completed" in order to expedite the approval process.</p>
                <div class="clearfix">&nbsp;</div>
                <h5>Thank You!</h5>

                <div class="clearfix">&nbsp;</div>
                <div class="text-center text-danger"> Need help? Call Member Support at <a href="tel: 720-445-4400" class="text-info"><strong>720-445-4400</strong></a></div>
                @elseif ($company_approval_status->pre_screening_process == 'completed')
                <p class="font-15 font-bold">Our Pre-screening process has completed and cleared!</p>
                <div class="clearfix">&nbsp;</div>
                <h5>Congratulations!</h5>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center text-danger"> Need help? Call Member Support at <a href="tel: 720-445-4400" class="text-info"><strong>720-445-4400</strong></a></div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
