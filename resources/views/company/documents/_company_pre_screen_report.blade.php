<div class="col-md-4">
    @php
        $card_psrf_cls = "card-primary";
        $card_psrf_header_cls = "border-primary";
        $card_psrf_text_cls = "text-primary";
    @endphp

    @if (!is_null($company_approval_status) && $company_approval_status->pre_screening_process == 'completed')
    @php
        $card_psrf_cls = "card-primary";
        $card_psrf_header_cls = "border-primary";
        $card_psrf_text_cls = "text-primary";
    @endphp
    @elseif (!is_null($company_approval_status) && $company_approval_status->pre_screening_process == 'in process')
    @php
        $card_psrf_cls = "card-primary";
        $card_psrf_header_cls = "border-primary";
        $card_psrf_text_cls = "text-primary";
    @endphp
    @endif
    <div class="card card-border {{ $card_psrf_cls }}">
        <div class="card-header {{ $card_psrf_header_cls }} bg-transparent text-left">
            <h3 class="card-title {{ $card_psrf_text_cls }} mb-0">Pre-Screening Report File</h3>
        </div>

        <div class="card-body">
            @if (!is_null($company_licensing) && !is_null($company_licensing->pre_screening_report_file_id))
            <a data-fancybox="gallery_customer_references_file"                
                href="{{ route('secure.file', ['path' => 'media/'.$company_licensing->pre_screening_report_file->media->file_name]) }}">
                <i class="far fa-file-pdf font-50"></i>
            </a>
            @endif
        </div>

        <div class="card-footer bg-transparent border-0">
            <div class="btn-group btn-group-solid">
                @if (!is_null($company_licensing) && !is_null($company_licensing->pre_screening_report_file_id))
                <a class="btn btn-primary btn-sm"  
                href="{{ route('secure.file', ['path' => 'media/'.$company_licensing->pre_screening_report_file->media->file_name]) }}" download> <i class="far fa-file-pdf"></i> &nbsp; Download </a>
                    
                <button class="btn btn-primary btn-sm waves-effect waves-light">Approved</button>
                @else
                <button class="btn btn-primary btn-sm waves-effect waves-light">Not Yet Received</button>
                @endif
            </div>
        </div>
    </div>
</div>