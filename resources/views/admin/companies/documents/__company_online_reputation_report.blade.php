<div class="col-md-4">
    @php
        $card_psrf_cls = "card-primary";
        $card_psrf_header_cls = "border-primary";
        $card_psrf_text_cls = "text-primary";
    @endphp

    @if (!is_null($company_approval_status) && $company_approval_status->online_reputation_report_status == 'completed')
    @php
        $card_psrf_cls = "card-primary";
        $card_psrf_header_cls = "border-primary";
        $card_psrf_text_cls = "text-primary";
    @endphp
    @elseif (!is_null($company_approval_status) && $company_approval_status->online_reputation_report_status == 'in process')
    @php
        $card_psrf_cls = "card-primary";
        $card_psrf_header_cls = "border-primary";
        $card_psrf_text_cls = "text-primary";
    @endphp
    @endif
    
    <div class="card card-border {{ $card_psrf_cls }}">
        <div class="card-header {{ $card_psrf_header_cls }} bg-transparent">
            <h3 class="card-title {{ $card_psrf_text_cls }} mb-0">Online Reputation Report</h3>
        </div>

        <div class="card-body">
            @if (!is_null($company_item->online_reputation_report_id))
            <a data-fancybox="gallery_online_reputation_report_file"                      
                href="{{ route('secure.file', ['path' => 'media/'.$company_item->online_reputation_report_file->media->file_name]) }}">
                <i class="far fa-file-pdf font-50"></i>
            </a>

            @else
            <p class="font-15">Not Yet Received</p>
            <div class="clearfix">&nbsp;</div>
            <a href="javascript:;" class="btn btn-danger btn-sm waves-effect waves-light uploadFile" data-toggle="modal" data-target="#CompanyFilesModal" data-expiry="no" data-document_type="online_reputation_report_file" data-field_name="online_reputation_report_id"><i class="fas fa-upload"></i> Upload</a>
            @endif
        </div>

        @if (!is_null($company_item->online_reputation_report_id))
        <div class="card-footer bg-transparent border-0">
            <div class="row">
                <div class="col-md-6">                       
                    <a class="btn btn-primary btn-sm"  href="{{ route('secure.file', ['path' => 'media/'.$company_item->online_reputation_report_file->media->file_name]) }}" download> <i class="far fa-file-pdf"></i> &nbsp; Download </a>
                </div>
                <div class="col-md-6 text-right">
                    <a href="javascript:;" class="btn btn-danger btn-sm remove_file" data-document_id="{{ $company_item->online_reputation_report_id }}" data-document_type="online_reputation_report_file" data-field_name="online_reputation_report_id">Delete</a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>