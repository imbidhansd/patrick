@php
$companyUserObj = Auth::guard('company_user')->user();
$company_information_item = \App\Models\CompanyInformation::where('company_id', $companyUserObj->company_id)->first();
//dd($company_information_item);
@endphp

@if ($company_approval_status->background_check_submittal != 'not required')
<li class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->background_check_submittal) }}">

    <a href="javascript:;" data-toggle="modal" data-target="#bgCheckModal">Background Check Submittal</a>

    @if ($company_approval_status->background_check_submittal == 'completed')
    {!! $company_approval_status->showStatusIcon($company_approval_status->background_check_submittal) !!}
    @elseif (is_null($companyUserObj->bg_check_status))
    {!! $company_approval_status->showStatusIcon('pending') !!}
    @elseif ($companyUserObj->bg_check_status == 'x:ready')
    {!! $company_approval_status->showStatusIcon('completed') !!}
    @else
    {!! $company_approval_status->showStatusIcon('in process') !!}
    @endif
</li>


<div class="modal fade" id="bgCheckModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Background Check Submittal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                @if($company_approval_status->background_check_submittal == 'completed')
                <p class="font-15">All background checks have been submitted and received.</p>
                @elseif (is_null($companyUserObj->bg_check_status))
                <p class="font-15">You have not yet submitted your background/credit check.</p>
                <p>Please submit your background/credit check as soon as possible to keep the application process moving swiftly.</p>
                <!-- <a href="{{ url('background-check') }}" class="btn btn-primary waves-effect waves-light">Click Here</a> -->
                <a href="{{ url('background-check') }}" class="btn btn-primary waves-effect waves-light">Click Here</a>
                @else
                <p class="font-15 font-bold">Background check has been submitted.</p>


                @if (!is_null($companyUserObj->bg_check_status) && $companyUserObj->bg_check_status != 'x:ready')
                <p>
                    <b>Status: </b>
                    <span class="badge badge-info">Pending</span>
                </p>
                @endif
                @endif
                <h5>Thank You!</h5>

                <div class="clearfix">&nbsp;</div>
                <div class="text-center text-danger"> Need help? Call Member Support at <a href="tel: 720-445-4400" class="text-info"><strong>720-445-4400</strong></a></div>
            </div>
        </div>
    </div>
</div>

@endif
