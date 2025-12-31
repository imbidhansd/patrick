@if (!is_null($company_approval_status) && $company_approval_status->subcontractor_agreement == 'in process')
<div class="col-md-4">
    <div class="card card-border card-primary">
        <div class="card-header border-primary bg-transparent">
            <h3 class="card-title text-primary mb-0">Subcontract Agreement</h3>
        </div>

        <div class="card-body">
            <a data-fancybox="gallery_written_warrenty_licensed_file"
                href="{{ route('secure.file', ['path' => 'media/'.$company_licensing->subcontractor_agreement_file->media->file_name]) }}">                
                <i class="far fa-file-pdf font-50"></i>
            </a>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-md-6">
                    <div class="btn-group btn-group-solid">
                        <a href="javascript:;" class="btn btn-warning btn-sm accept_file" data-toggle="modal" data-target="#acceptCompanyDocumentModal" data-expiry="no" data-document_id="{{ $company_licensing->subcontractor_agreement_file_id }}" data-document_type="subcontractor_agreement_file">Accept</a>

                        <a href="javascript:;" class="btn btn-danger btn-sm reject_file" data-toggle="modal" data-target="#rejectCompanyDocumentModal" data-document_id="{{ $company_licensing->subcontractor_agreement_file_id }}" data-document_type="subcontractor_agreement_file">Reject</a>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <a href="javascript:;" class="btn btn-danger btn-sm remove_file" data-document_id="{{ $company_licensing->subcontractor_agreement_file_id }}" data-document_type="subcontractor_agreement_file" data-field_name="subcontractor_agreement_file_id">Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif