@php $trade_word = 'General'; @endphp
@if ($company_item->trade_id == 2)
@php $trade_word = 'Professional'; @endphp
@endif

@if ($company_approval_status->general_liablity_insurance_file != 'not required')

<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->general_liablity_insurance_file) }}">
    @if ($company_approval_status->general_liablity_insurance_file == 'pending')
    <a href="javascript:;" data-toggle="modal" data-target="#pendingLiabilityInsuranceModal">{{ $trade_word }} Liability Insurance</a>

    <div class="modal fade" id="pendingLiabilityInsuranceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content ">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold text-left">{{ $trade_word }} Liability Insurance Certificate Holder</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body text-center">
                    <p class="font-15">We have not yet received a copy of your {{ $trade_word }} liability insurance from your insurance provider showing Trust Patrick as a certificate holder.</p>
                    <p class="font-15">If you have not done so already, please fill out the forms necessary to send to your insurance agent/agents.</p>
                    <p class="font-15">Once your insurance company has provided us with the insurance document showing Trust Patrick as a certificate holder on your {{ $trade_word }} liability insurance policy, we will upload this document into the system and mark it as completed.</p>
                    <p class="font-15">{{ $trade_word }} Liability Certificate Holder Request Form</p>

                    <div class="btn-group btn-group-solid">
                       <a href="{{ url('account/application/liability-insurance-view') }}" target="_blank" class="btn btn-info btn-sm">View Request Form</a>
                        <a href="{{ url('account/application/liability-insurance-download') }}" class="btn btn-primary btn-sm" download>Download Form</a>
                    </div>
                    <div class="clearfix">&nbsp;</div>

                    <a href="javascript:;" class="btn btn-primary btn-sm mark_as_completed_insurance" data-type="liability_insurance">I've done this!</a>
                    <div class="clearfix">&nbsp;</div>
                    <h5>Thank You!</h5>
                </div>
            </div>
        </div>
    </div>
    @elseif ($company_approval_status->general_liablity_insurance_file == 'in process')
    <a href="javascript:;" data-toggle="modal" data-target="#inProcessLiabilityInsuranceModal">{{ $trade_word }} Liability Insurance</a>

    <div class="modal fade" id="inProcessLiabilityInsuranceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content ">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold text-left">{{ $trade_word }} Liability Insurance Certificate Holder</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body text-center">
                    <p class="font-15">We have not yet received a copy of your {{ $trade_word }} liability insurance from your insurance provider showing Trust Patrick as a certificate holder.</p>
                    @if (!is_null($company_insurance->general_liability_insurance_mark_as_completed_date))
                    <p class="font-15">You marked this as completed on ({{ \App\Models\Custom::date_formats($company_insurance->general_liability_insurance_mark_as_completed_date, env('DB_DATE_FORMAT'), env('DATE_FORMAT')) }}).</p>
                    @endif
                    <p class="font-15">If it has been several days since your request, please contact your insurance provider and followup up with them to keep the approval process moving swiftly.</p>
                    <div class="clearfix">&nbsp;</div>
                    <h5>Thank You!</h5>
                </div>
            </div>
        </div>
    </div>
    @elseif ($company_approval_status->general_liablity_insurance_file == 'completed')
    <a href="javascript:;" data-toggle="modal" data-target="#completedLiabilityInsuranceModal">{{ $trade_word }} Liability Insurance</a>
    <div class="modal fade" id="completedLiabilityInsuranceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content ">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold text-left">{{ $trade_word }} Liability Insurance Certificate Holder</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body text-center">
                    <p class="font-15 font-bold">We've received your {{ $trade_word }} liability documentation.</p>
                    <div class="clearfix">&nbsp;</div>
                    <h5>Thank You!</h5>
                </div>
            </div>
        </div>
    </div>
    @endif
    {!! $company_approval_status->showStatusIcon($company_approval_status->general_liablity_insurance_file) !!}
</li>
@endif