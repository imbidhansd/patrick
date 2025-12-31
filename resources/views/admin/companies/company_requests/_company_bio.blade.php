@if (!is_null($company_approval_status) && $company_approval_status->company_bio == 'in process')
    <div class="col-md-12">
        <div class="card card-border card-primary">
            <div class="card-header border-primary bg-transparent">
                <h3 class="card-title text-primary mb-0">Company Bio</h3>
            </div>

            <div class="card-body">
                {!! $company_item->company_bio !!}
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-md-6">
                        <div class="btn-group btn-group-solid">
                            <a href="javascript:;" class="btn btn-warning btn-sm accept_company_bio">Accept</a>

                            <a href="javascript:;" class="btn btn-danger btn-sm reject_company_bio" data-toggle="modal" data-target="#rejectCompanyInfoModal">Reject</a>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="javascript:;" class="btn btn-danger btn-sm remove_company_bio">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif