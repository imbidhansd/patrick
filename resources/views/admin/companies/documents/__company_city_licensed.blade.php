@if (!is_null($company_licensing) && $company_licensing->city_licensed == 'yes')
<div class="col-md-4">
    @php
        $card_colors = \App\Models\Custom::company_document_card_colors($company_approval_status->city_licensing, $company_licensing->city_licensed_file_id);

        list('card_cls' => $card_cls, 'card_header_cls' => $card_header_cls, 'card_text_cls' => $card_text_cls) = $card_colors;
    @endphp

    <div class="card card-border {{ $card_cls }}">
        <div class="card-header {{ $card_header_cls }} bg-transparent">
            <h3 class="card-title {{ $card_text_cls }} mb-0">City Licensed</h3>
        </div>

        <div class="card-body">
            @if (!is_null($company_licensing->city_licensed_file_id))
            <a data-fancybox="gallery_city_licensed_file"
                href="{{ route('secure.file', ['path' => 'media/'.$company_licensing->city_licensed_file->media->file_name]) }}">
                <i class="far fa-file-pdf font-50"></i>
            </a>

            @else
            <p class="font-15">Not Yet Received</p>
            <div class="clearfix">&nbsp;</div>
            <a href="javascript:;" class="btn btn-danger btn-sm waves-effect waves-light uploadFile" data-toggle="modal"
                data-target="#CompanyFilesModal" data-expiry="yes" data-document_type="city_licensing" data-field_name="city_licensed_file_id"><i class="fas fa-upload"></i> Upload</a>
            @endif
        </div>

        @if (!is_null($company_approval_status) && !is_null($company_licensing->city_licensed_file_id))
        <div class="card-footer bg-transparent border-0">
            <div class="row">
                <div class="col-md-6">
                    @if ($company_approval_status->city_licensing == 'in process')
                    <div class="btn-group btn-group-solid">
                        <a href="javascript:;" class="btn btn-warning btn-sm accept_file" data-expiry="yes" data-toggle="modal" data-target="#acceptCompanyDocumentModal" data-document_id="{{ $company_licensing->city_licensed_file_id }}" data-document_type="city_licensing">Accept</a>

                        <a href="javascript:;" class="btn btn-danger btn-sm reject_file" data-expiry="yes" data-toggle="modal" data-target="#rejectCompanyDocumentModal" data-document_id="{{ $company_licensing->city_licensed_file_id }}" data-document_type="city_licensing">Reject</a>
                    </div>
                    @elseif ($company_approval_status->city_licensing == 'pending')
                    <span class="badge badge-danger big-badge">Rejected</span>
                    @elseif ($company_approval_status->city_licensing == 'completed')                                        
                     <a class="btn btn-primary btn-sm" href="{{ route('secure.file', ['path' => 'media/'.$company_licensing->city_licensed_file->media->file_name]) }}" download> <i class="far fa-file-pdf"></i> &nbsp; Download </a>
                    @endif
                </div>
                <div class="col-md-6 text-right">
                    <a href="javascript:;" class="btn btn-danger btn-sm remove_file" data-document_id="{{ $company_licensing->city_licensed_file_id }}" data-document_type="city_licensing" data-field_name="city_licensed_file_id">Delete</a>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endif