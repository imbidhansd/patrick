@php
$companyUserObj = Auth::guard('company_user')->user();
$company_information_item = \App\Models\CompanyInformation::where('company_id', $companyUserObj->company_id)->first();
@endphp

@if ($company_approval_status->background_check_process != 'not required')
<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->background_check_process) }}">

    <a href="javascript:;" data-toggle="modal" data-target="#backgroundCheckProcessModal">Background Check Process</a>

    {!! $company_approval_status->showStatusIcon($company_approval_status->background_check_process) !!}
</li>

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
                    @if (Auth::guard('company_user')->user()->company_user_type == 'company_super_admin')
                        <p class="font-15">The background check process has not yet started.</p>

                        @if ($company_item->number_of_owners > 1 && $company_approval_status->background_check_submittal != 'completed')
                        <p class="text-danger">The additional owners(s) of your company have not yet submitted their background check(s). Please remind other owners(s) to submit their background check(s) as soon as possible to keep the approval process moving swiftly.</p>
                        @endif

                        @if ($company_item->number_of_owners > 1 && ($company_information_item->company_owner_2_status == 'pending' || $company_information_item->company_owner_3_status == 'pending' || $company_information_item->company_owner_4_status == 'pending'))
                        <p class="text-danger"><a href="{{ url('company-owners') }}">Click here</a> to send invitation to other owners</p>
                        @else
                        <p class="text-danger">Please submit your background check.</p>
                        @endif
                    @else
                        <p class="font-15">Your background check process has not yet been submitted. Please submit your background check as soon as possible to keep the approval process running smoothly.</p>
                    @endif
                    <div class="clearfix">&nbsp;</div>
                @elseif ($company_approval_status->background_check_process == 'in process')
                    <p class="font-15">All background checks have been submitted and are in progress. </p>
                    <p>
                        <b>Status: </b>
                        <span class="badge badge-info">Pending</span>
                    </p>
                @elseif ($company_approval_status->background_check_process == 'completed')
                    <p class="font-15 font-bold">All background checks have been completed.</p>
                @endif
                <h5>Thank You!</h5>
            </div>
        </div>
    </div>
</div>
@endif
