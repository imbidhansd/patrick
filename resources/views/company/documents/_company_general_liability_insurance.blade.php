@if (!is_null($company_insurances))
    @php $trade_word = 'General'; @endphp
    @if ($company_item->trade_id == 2)
    @php $trade_word = 'Professional'; @endphp
    @endif
    
    @php
        $card_cls = "card-primary";
        $card_header_cls = "border-primary";
        $card_text_cls = "text-primary";
    @endphp
    
    @if (!is_null($company_approval_status) && $company_approval_status->general_liablity_insurance_file == 'completed')
    @php
        $card_cls = "card-primary";
        $card_header_cls = "border-primary";
        $card_text_cls = "text-primary";
    @endphp
    @elseif (!is_null($company_approval_status) && $company_approval_status->general_liablity_insurance_file == 'in process')
    @php
        $card_cls = "card-primary";
        $card_header_cls = "border-primary";
        $card_text_cls = "text-primary";
    @endphp
    @endif
    
    <div class="col-md-6 col-lg-4">
        <div class="card card-border {{ $card_cls }}">
            <div class="card-header {{ $card_header_cls }} bg-transparent text-left">
                <h3 class="card-title {{ $card_text_cls }} mb-0">{{ $trade_word }} Liability Insurance Agent Agency File</h3>
            </div>

            <div class="card-body">
                @if (!is_null($company_insurances) && !is_null($company_insurances->gen_lia_ins_file_id))
                    <a data-fancybox="gallery_liability_insurance_file" href="{{ route('secure.file.company', ['path' => 'media/'.$company_insurances->liability_insurance_file->media->file_name]) }}">
                        <i class="far fa-file-pdf font-50"></i>
                    </a>
                @endif
            </div>

            <div class="card-footer bg-transparent border-0">
                <div class="btn-group btn-group-solid">
                    @if (!is_null($company_approval_status) && !is_null($company_insurances) && !is_null($company_insurances->gen_lia_ins_file_id))

                    <a class="btn btn-primary btn-sm" href="{{ route('secure.file.company', ['path' => 'media/'.$company_insurances->liability_insurance_file->media->file_name]) }}" download> <i class="far fa-file-pdf"></i> &nbsp; Download </a>

                    <button class="btn btn-primary btn-sm waves-effect waves-light">Approved</button>

                    @else
                    <p>
                        Awaiting Documentation from Insurance Company. <br />
                        This Document uploaded by {{ env('SITE_TITLE') }} once received!
                    </p>
                    <?php /* <button class="btn btn-primary btn-sm waves-effect waves-light">Not Yet Received</button> */ ?>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif