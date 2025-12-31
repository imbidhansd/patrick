<div class="card-header bg-secondary text-white">
    <div class="card-widgets">
        <b class="badge badge-dark">Public</b>
    </div>
    <h3 class="card-title text-white mb-0">Company Bio</h3>
</div>

<div class="card-body">
    <div class="text-left">
        {!! $company_item->company_bio !!}
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
</div>

<div class="card-footer b-0 bc-none">
    <div class="row">
        <div class="col-md-6">
            @if (isset($admin_form) && $admin_form)
            @if (!is_null($company_approval_status) && $company_approval_status->company_bio == 'completed')
            <span class="badge badge-primary">Approved</span>
            @elseif (!is_null($company_approval_status) && $company_approval_status->company_bio == 'pending' && !is_null($company_approval_status->company_bio_reject_note))
            <span class="badge badge-danger">Rejected</span>
            @elseif (!is_null($company_approval_status) && $company_approval_status->company_bio == 'in process')
            <div class="btn-group btn-group-solid">
                <a href="javascript:;" class="btn btn-primary btn-sm accept_company_bio">Accept</a>
                <a href="javascript:;" class="btn btn-danger btn-sm reject_company_bio" data-toggle="modal" data-target="#rejectCompanyInfoModal">Reject</a>
            </div>
            @endif
            @elseif (!is_null($company_approval_status) && $company_approval_status->company_bio == 'in process')
            <a href="javascript:;" class="btn btn-warning btn-xs">Pending Approval</a>
            @endif
        </div>
        <div class="col-md-6 text-right">
            <div class="btn-group btn-group-solid">
                @if (isset($admin_form) && $admin_form && !is_null($company_item->company_bio))
                <a href="javascript:;" class="btn btn-danger btn-sm remove_company_bio">Delete Company Bio</a>
                @endif
                <a href="javascript:;" title="Edit Company Bio Information" data-toggle="modal" data-target="#udpateCompanyBioModal" class="btn btn-sm btn-primary">Edit</a>
            </div>

        </div>
    </div>
</div>
