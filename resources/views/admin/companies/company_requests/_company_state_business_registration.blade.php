@if (!is_null($company_approval_status) && $company_approval_status->registered_legally_to_state == 'in process')
<div class="col-md-4">
    <div class="card card-border card-primary">
        <div class="card-header border-primary bg-transparent">
            <h3 class="card-title text-primary mb-0">State Business Registration</h3>
        </div>

        <div class="card-body">
            <a data-fancybox="gallery_state_business_registeration_file" 
             href="{{ route('secure.file', ['path' => 'media/'.$company_licensing->state_business_registeration_file->media->file_name]) }}">            
                <i class="far fa-file-pdf font-50"></i>
            </a>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-md-6">
                    <div class="btn-group btn-group-solid">
                        <a href="javascript:;" class="btn btn-warning btn-sm accept_file" data-toggle="modal" data-target="#acceptCompanyDocumentModal" data-expiry="yes" data-document_id="{{ $company_licensing->state_business_registeration_file_id }}" data-document_type="registered_legally_to_state">Accept</a>

                        <a href="javascript:;" class="btn btn-danger btn-sm reject_file" data-toggle="modal" data-target="#rejectCompanyDocumentModal" data-document_id="{{ $company_licensing->state_business_registeration_file_id }}" data-document_type="registered_legally_to_state">Reject</a>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <a href="javascript:;" class="btn btn-danger btn-sm remove_file" data-document_id="{{ $company_licensing->state_business_registeration_file_id }}" data-document_type="registered_legally_to_state" data-field_name="state_business_registeration_file_id">Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif