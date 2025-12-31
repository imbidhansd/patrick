@if (!is_null($company_approval_status) && !is_null($company_customer_references->customer_reference_file) && $company_approval_status->customer_references == 'in process')
<div class="col-md-4">
    <div class="card card-border card-primary">
        <div class="card-header border-primary bg-transparent">
            <h3 class="card-title text-primary mb-0">Customer References</h3>
        </div>

        @if (!is_null($company_customer_references->customer_reference_file->media))
        <div class="card-body">                           
            <a data-fancybox="gallery_proof_of_ownership" href="{{ route('secure.file', ['path' => 'media/'.$company_customer_references->customer_reference_file->media->file_name]) }}">
                <i class="far fa-file-pdf font-50"></i>
            </a>
        </div>
        @endif

        <div class="card-footer">
            <div class="row">
                <div class="col-md-6">
                    <div class="btn-group btn-group-solid">
                        <a href="javascript:;" class="btn btn-warning btn-sm accept_file" data-toggle="modal" data-target="#acceptCompanyDocumentModal" data-expiry="no" data-document_id="{{ $company_customer_references->customer_references_file_id }}" data-document_type="customer_references">Accept</a>

                        <a href="javascript:;" class="btn btn-danger btn-sm reject_file" data-toggle="modal" data-target="#rejectCompanyDocumentModal" data-document_id="{{ $company_customer_references->customer_references_file_id }}" data-document_type="customer_references">Reject</a>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <a href="javascript:;" class="btn btn-danger btn-sm remove_file" data-document_id="{{ $company_customer_references->customer_references_file_id }}" data-document_type="customer_references" data-field_name="customer_references_file_id">Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
