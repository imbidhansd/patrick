@if (!is_null($company_insurances) && !is_null($company_approval_status) && $company_approval_status->general_liablity_insurance_file != 'not required')

    @php $trade_word = 'General'; @endphp
    @if ($company_item->trade_id == 2)
    @php $trade_word = 'Professional'; @endphp
    @endif
    
    @php
        $card_cls = "card-primary";
        $card_header_cls = "border-primary";
        $card_text_cls = "text-primary";
    @endphp
    
    @if ($company_approval_status->general_liablity_insurance_file == 'pending')
    @php
        $card_cls = "card-primary";
        $card_header_cls = "border-primary";
        $card_text_cls = "text-primary";
    @endphp
    @endif
<div class="col-md-4">
    <div class="card card-border {{ $card_cls }}">
        <div class="card-header {{ $card_header_cls }} bg-transparent">
            <h3 class="card-title {{ $card_text_cls }} mb-0">{{ $trade_word }} Liability Insurance Agent Agency Document</h3>
        </div>

        <div class="card-body">
            @if (!is_null($company_insurances) && !is_null($company_insurances->gen_lia_ins_file_id))
                <a data-fancybox="gallery_liability_insurance_file" 
                href="{{ route('secure.file', ['path' => 'media/'.$company_insurances->liability_insurance_file->media->file_name]) }}">                
                    <i class="far fa-file-pdf font-50"></i>
                </a>
            @else
                <p class="font-15">Not Yet Received</p>

                {!! Form::hidden('insurance_type', 'general_liablity_insurance_file', ['id' => 'insurance_type']) !!}
                <div class="clearfix">&nbsp;</div>
                <a href="javascript:;" class="btn btn-danger btn-sm waves-effect waves-light uploadFile" data-toggle="modal" data-target="#CompanyFilesModal" data-expiry="yes" data-document_type="general_liablity_insurance_file" data-field_name="gen_lia_ins_file_id"><i class="fas fa-upload"></i> Upload</a>
            @endif
        </div>

        @if (!is_null($company_approval_status) && !is_null($company_insurances) && !is_null($company_insurances->gen_lia_ins_file_id))
        <div class="card-footer bg-transparent border-0">
            <div class="row">
                <div class="col-md-6">
                    <a class="btn btn-primary btn-sm" 
                    href="{{ route('secure.file', ['path' => 'media/'.$company_insurances->liability_insurance_file->media->file_name]) }}" download> <i class="far fa-file-pdf"></i> &nbsp; Download </a>                    
                </div>
                <div class="col-md-6 text-right">
                    <a href="javascript:;" class="btn btn-danger btn-sm remove_file" data-document_id="{{ $company_insurances->gen_lia_ins_file_id }}" data-document_type="general_liablity_insurance_file" data-field_name="gen_lia_ins_file_id">Delete</a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endif