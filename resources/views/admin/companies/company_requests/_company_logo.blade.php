@if (!is_null($company_approval_status) && $company_approval_status->company_logo == 'in process')
<div class="col-md-4">
    <div class="card card-border card-primary">
        <div class="card-header border-primary bg-transparent">
            <h3 class="card-title text-primary mb-0">Company Logo</h3>
        </div>

        <div class="card-body">
            @if (!is_null($company_item->company_logo))
            <a href="{{ route('secure.file', ['path' => 'media/'.$company_item->company_logo->file_name]) }}"
                data-fancybox="gallery">
                <img src="{{ asset('/') }}/uploads/media/fit_thumbs/40x40/{{ $company_item->company_logo->file_name }}"
                    class='img-thumbnail' />
            </a>
            @endif
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-md-6">
                    <div class="btn-group btn-group-solid">
                        <a href="javascript:;" class="btn btn-warning btn-sm accept_company_logo">Accept</a>
                        <a href="javascript:;" class="btn btn-danger btn-sm reject_company_logo" data-toggle="modal" data-target="#rejectCompanyInfoModal">Reject</a>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <a href="javascript:;" class="btn btn-danger btn-sm remove_company_logo">Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif