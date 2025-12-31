<div class="col-md-4">
    @php
        $card_colors = \App\Models\Custom::company_bio_logo_card_colors($company_approval_status->company_logo, $company_approval_status->company_logo_reject_note);

        list('card_cls' => $card_cls, 'card_header_cls' => $card_header_cls, 'card_text_cls' => $card_text_cls) = $card_colors;
    @endphp
    
    <div class="card card-border {{ $card_cls }}">
        <div class="card-header {{ $card_header_cls }} bg-transparent text-left">
            <h3 class="card-title {{ $card_text_cls }} mb-0">Company Logo</h3>
        </div>
        <div class="card-body text-center">
            @if (!is_null($company_item->company_logo))
            <a href="{{ asset('/') }}uploads/media/{{ $company_item->company_logo->file_name }}"
                data-fancybox="gallery">
                <img src="{{ asset('/') }}/uploads/media/fit_thumbs/40x40/{{ $company_item->company_logo->file_name }}"
                    class='img-thumbnail' />
            </a>
            @endif
        </div>

        @if (!is_null($company_approval_status))
        <div class="card-footer bg-transparent border-0">
            <div class="btn-group btn-group-solid">
                @if ($company_approval_status->company_logo == 'pending')
                <a href="javascript:;" class="btn btn-primary btn-sm waves-effect waves-light" data-toggle="modal" data-target="#uploadCompanyLogo"><i class="fas fa-upload"></i> Upload</a>
                @elseif($company_approval_status->company_logo == 'in process')
                <button class="btn btn-primary btn-sm waves-effect waves-light">Awaiting for Approval</button>
                @elseif($company_approval_status->company_logo == 'completed')
                <button class="btn btn-primary btn-sm waves-effect waves-light">Approved</button>
                @endif

                @if ($company_approval_status->company_logo == 'pending' && !is_null($company_approval_status->company_logo_reject_note))
                <a href="javascript:;" class="btn btn-danger btn-sm waves-effect waves-light" data-toggle="modal" data-target="#companyLogoRejectModal" title="Show Reject Note">Rejected</a>

                <div class="modal fade" id="companyLogoRejectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header text-center">
                                <h4 class="modal-title w-100 font-weight-bold text-left">
                                    Company Logo Reject Note
                                </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body text-left">
                                <p>
                                    <b>Reject note:</b> {!! $company_approval_status->company_logo_reject_note !!}
                                </p>
                                
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
        @endif
    </div>
</div>