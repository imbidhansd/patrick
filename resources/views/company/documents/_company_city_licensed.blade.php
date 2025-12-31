@if (!is_null($company_licensing) && $company_licensing->city_licensed == 'yes')
<div class="col-md-6 col-lg-4">
    @php
        $card_colors = \App\Models\Custom::company_document_card_colors($company_approval_status->city_licensing, $company_licensing->city_licensed_file_id);

        list('card_cls' => $card_cls, 'card_header_cls' => $card_header_cls, 'card_text_cls' => $card_text_cls) = $card_colors;
    @endphp
    
    <div class="card card-border {{ $card_cls }}">
        <div class="card-header {{ $card_header_cls }} bg-transparent text-left">
            <h3 class="card-title {{ $card_text_cls }} mb-0">City Licensed</h3>
        </div>

        <div class="card-body">
            @if (!is_null($company_licensing->city_licensed_file_id))
            <a data-fancybox="gallery_city_licensed_file"
                href="{{ route('secure.file.company', ['path' => 'media/'.$company_licensing->city_licensed_file->media->file_name]) }}">
                <i class="far fa-file-pdf font-50"></i>
            </a>

            @else
            Not Yet Received
            @endif
        </div>

        <div class="card-footer bg-transparent border-0">
            <div class="btn-group btn-group-solid">
                @if (!is_null($company_licensing->city_licensed_file_id) && $company_approval_status->city_licensing == 'completed')
                <a href="{{ route('secure.file.company', ['path' => 'media/'.$company_licensing->city_licensed_file->media->file_name]) }}" class="btn btn-primary btn-sm waves-effect waves-light" download><i class="far fa-file-pdf"></i> Download</a>
                @endif

                @if (is_null($company_licensing->city_licensed_file_id) || $company_approval_status->city_licensing == 'pending')
        
                    <a href="javascript:;" class="btn btn-primary btn-sm waves-effect waves-light" data-toggle="modal" data-target="#cityLicensingModal"><i class="fas fa-upload"></i> Upload</a>

                    @if ($company_approval_status->city_licensing == 'pending' && !is_null($company_licensing->city_licensed_file_id))
                        <a href="javascript:;" class="btn btn-danger btn-sm waves-effect waves-light" data-toggle="modal" data-target="#cityLicencingRejectModal"title="Show Reject Note">Rejected</a>

                        @if (!is_null($company_licensing->city_licensed_file->reject_note))
                        <div class="modal fade" id="cityLicencingRejectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-md" role="document">
                                <div class="modal-content">
                                    <div class="modal-header text-center">
                                        <h4 class="modal-title w-100 font-weight-bold text-left">
                                            City Licensed Reject Note
                                        </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body text-left">
                                        @if (!is_null($company_licensing->city_licensed_file->expiration_date))
                                        <p><b>Expiry Date: </b> {!! \App\Models\Custom::date_formats($company_licensing->city_licensed_file->expiration_date, env('DB_DATE_FORMAT'), env('DATE_FORMAT')) !!} </p>
                                        @endif
                                        
                                        <p>
                                            <b>Reject note:</b> {!! $company_licensing->city_licensed_file->reject_note !!}
                                        </p>
                                        
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endif
                @elseif ($company_approval_status->city_licensing == 'in process')
                <button class="btn btn-primary btn-sm waves-effect waves-light">Awaiting for Approval</button>
                @elseif ($company_approval_status->city_licensing == 'completed')
                <button class="btn btn-primary btn-sm waves-effect waves-light">Approved</button>
                @endif
            </div>
        </div>
    </div>
</div>
@endif