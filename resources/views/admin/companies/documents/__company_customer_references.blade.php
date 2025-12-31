<div class="col-md-4">
    @php
        $card_cref_cls = "card-primary";
        $card_cref_header_cls = "border-primary";
        $card_cref_text_cls = "text-primary";
    @endphp

    @if (!is_null($company_approval_status) && $company_approval_status->customer_references == 'completed')
    @php
        $card_cref_cls = "card-primary";
        $card_cref_header_cls = "border-primary";
        $card_cref_text_cls = "text-primary";
    @endphp
    @elseif (!is_null($company_approval_status) && $company_approval_status->customer_references == 'in process')
    @php
        $card_cref_cls = "card-primary";
        $card_cref_header_cls = "border-primary";
        $card_cref_text_cls = "text-primary";
    @endphp
    @endif

    <div class="card card-border {{ $card_cref_cls }}">
        <div class="card-header {{ $card_cref_header_cls }} bg-transparent">
            <h3 class="card-title {{ $card_cref_text_cls }} mb-0">Customer Reference</h3>
        </div>

        <div class="card-body">
            @if (!is_null($company_customer_references) && !is_null($company_customer_references->customer_references_file_id))
            <a data-fancybox="gallery_customer_references_file"                
                href="{{ route('secure.file', ['path' => 'media/'.$company_customer_references->customer_reference_file->media->file_name]) }}">
                <i class="far fa-file-pdf font-50"></i>
            </a>

            @else
            <p class="font-15">Not Yet Received</p>
            <div class="clearfix">&nbsp;</div>
            <a href="javascript:;" class="btn btn-danger btn-sm waves-effect waves-light uploadFile" data-toggle="modal" data-target="#CompanyFilesModal" data-expiry="no" data-document_type="customer_references" data-field_name="customer_references_file_id"><i class="fas fa-upload"></i> Upload</a>
            @endif
        </div>

        @if (!is_null($company_customer_references) && !is_null($company_customer_references->customer_references_file_id))
        <div class="card-footer bg-transparent border-0">
            <div class="row">
                <div class="col-md-6">
                    @if ($company_approval_status->customer_references == 'in process')
                    <div class="btn-group btn-group-solid">
                        <a href="javascript:;" class="btn btn-warning btn-sm accept_file" data-expiry="no" data-toggle="modal" data-target="#acceptCompanyDocumentModal" data-document_id="{{ $company_customer_references->customer_references_file_id }}" data-document_type="customer_references">Accept</a>

                        <a href="javascript:;" class="btn btn-danger btn-sm reject_file" data-expiry="yes" data-toggle="modal" data-target="#rejectCompanyDocumentModal" data-document_id="{{ $company_customer_references->customer_references_file_id }}" data-document_type="customer_references">Reject</a>
                    </div>
                    @elseif ($company_approval_status->customer_references == 'pending')
                    <span class="badge badge-danger big-badge">Rejected</span>
                    @elseif ($company_approval_status->customer_references == 'completed')                                                        
                    <a class="btn btn-primary btn-sm" href="{{ route('secure.file', ['path' => 'media/'.$company_customer_references->customer_reference_file->media->file_name]) }}" download> <i class="far fa-file-pdf"></i> &nbsp; Download </a>
                    @endif
                </div>
                <div class="col-md-6 text-right">
                    <a href="javascript:;" class="btn btn-danger btn-sm remove_file" data-document_id="{{ $company_customer_references->customer_references_file_id }}" data-document_type="customer_references" data-field_name="customer_references_file_id">Delete</a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>