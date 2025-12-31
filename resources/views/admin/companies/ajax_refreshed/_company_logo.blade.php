<div class="card-header bg-secondary text-white">
    <div class="card-widgets">
        <b class="badge badge-dark">Public</b>
    </div>
    <h3 class="card-title text-white mb-0">Company Logo</h3>
</div>

<div class="card-body">
    <p class="text-center text-danger pt-2 pb-0 mb-3">Please upload a logo minimum dimension of {{ env('MAX_LOGO_WIDTH') }}(w) x {{ env('MAX_LOGO_HEIGHT') }}(h)</p>
    <div class="row">
        <div class="col-sm-12">
            @include('admin.companies._company_logo_upload')
        </div>
    </div>
</div>

@if (isset($admin_form) && $admin_form && !is_null($company_item->company_logo))
<div class="card-footer b-0 bc-none">
    <div class="row">
        <div class="col-md-6">
            @if (!is_null($company_approval_status) && $company_approval_status->company_logo == 'completed')
            <span class="badge badge-primary">Approved</span>
            @elseif (!is_null($company_approval_status) && $company_approval_status->company_logo == 'pending' && !is_null($company_approval_status->company_logo_reject_note))
            <span class="badge badge-danger">Rejected</span>
            @elseif (!is_null($company_approval_status) && $company_approval_status->company_logo == 'in process')
            <div class="btn-group btn-group-solid">
                <a href="javascript:;" class="btn btn-primary btn-sm accept_company_logo">Accept</a>

                <a href="javascript:;" class="btn btn-danger btn-sm reject_company_logo" data-toggle="modal" data-target="#rejectCompanyInfoModal">Reject</a>
            </div>
            @endif
        </div>
        <div class="col-md-6 text-right">
            <div class="btn-group btn-group-solid">
                <a href="javascript:;" class="btn btn-danger btn-sm remove_company_logo">Delete Company Logo</a>
            </div>
        </div>
    </div>
</div>
@endif