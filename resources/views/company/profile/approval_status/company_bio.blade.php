@if ($company_approval_status->company_bio != 'not required')
<li
class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->company_bio) }}">

@if ($company_approval_status->company_bio == 'pending')
<a href="javascript:;" data-toggle="modal" data-target="#companyBioModal">Company Bio</a>
@elseif ($company_approval_status->company_bio == 'in process')
<a href="javascript:;" data-toggle="modal" data-target="#companyBioStatusModal">Company Bio</a>
@elseif ($company_approval_status->company_bio == 'completed')
<a href="javascript:;" data-toggle="modal" data-target="#companyBioStatusModal">Company Bio</a>
@else
Company Bio
@endif

{!! $company_approval_status->showStatusIcon($company_approval_status->company_bio) !!}
</li>

@if ($company_approval_status->company_bio == 'pending' || $company_approval_status->company_bio == 'in process')
<div class="modal fade" id="companyBioModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
aria-hidden="true" style="overflow-y: scroll;">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
        <div class="modal-header text-center">
            <h4 class="modal-title w-100 font-weight-bold text-left">Update Company Contact Info</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        {!! Form::open(['url' => url('update-company-profile'), 'class' => 'module_form', 'id' => 'company_bio_update_form']) !!}
        {!! Form::hidden('update_type', 'company_bio') !!}
        <div class="modal-body text-left">
            <p class="text-center font-15">Your company bio is the first exposure people visiting our websites have to your company so make it stand out! Take the time to write a great bio or copy and paste from your company website.</p>

            <div class="form-group">
                {!! Form::label('Company Bio') !!}
                {!! Form::textarea('company_bio', $company_item->company_bio, ['class' => 'form-control
                summernote', 'required' => false]) !!}
            </div>

            @if (!is_null($company_approval_status) && $company_approval_status->company_bio == 'pending' &&
            !is_null($company_approval_status->company_bio_reject_note))
            <div class="clearfix"></div>
            <div class="text-left mt-4">
                <p class="text-danger font-bold mb-1">Your company Bio has been rejected.</p>
                <p class="text-dark_grey"><b class="text-danger">Reason: </b> {!! $company_approval_status->company_bio_reject_note !!}</p>
                <p class="text-danger">Please update your company bio and resubmit. Thank you</p>
            </div>
            @endif


            <p class="text-center font-15">Per our policy, company bio cannot contain phone numbers, website addresses, custom text numbers, or any other contact information.</p>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary waves-effect waves-light company_bio_update_btn">Save changes</button>
            <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
</div>
@endif


@if ($company_approval_status->company_bio == 'in process' || $company_approval_status->company_bio == 'completed')
<div class="modal fade" id="companyBioStatusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-md" role="document">
    <div class="modal-content ">
        <div class="modal-header text-center">
            <h4 class="modal-title w-100 font-weight-bold text-left">Update Company Contact Info</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body text-center">
            @if ($company_approval_status->company_bio == 'in process')
            <h2>Pending Review</h2>
            <p class="font-15">Thank you for submitting your Company Bio!</p>
            @elseif ($company_approval_status->company_bio == 'completed')
            <p class="font-15 font-bold">Thank you for submitting your Company Bio!</p>
            @endif
            <h5>Thank You!</h5>

            @if ($company_approval_status->company_bio == 'in process')
            <div class="btn-group btn-group-solid">
                <a href="javascript:;" class="btn btn-primary btn-sm close_current_modal" data-toggle="modal" data-target="#companyBioModal">Update Company Bio</a>
            </div>
            @endif
        </div>
    </div>
</div>
</div>
@endif
@endif


@push('additional_scripts')
<script type="text/javascript">
    $('#company_bio_update_form').submit (function(){
        $(this).find(".company_bio_update_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>').attr('disabled', true);
    });
</script>
@endpush
