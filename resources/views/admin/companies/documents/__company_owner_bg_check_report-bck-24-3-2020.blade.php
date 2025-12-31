@php
    $company_owner = 'company_owner'.$bg_check;
    $owner_status = 'owner_'.$bg_check.'_bg_check_document_status';
    $document_type = 'owner_'.$bg_check.'_bg_check_file';
@endphp
@if (!is_null($company_information) && !is_null($company_information->$company_owner))
<div class="col-md-4">
    @php
        $card_psrf_cls = "card-primary";
        $card_psrf_header_cls = "border-primary";
        $card_psrf_text_cls = "text-primary";
    @endphp

    @if (!is_null($company_approval_status) && $company_approval_status->$owner_status == 'completed')
    @php
        $card_psrf_cls = "card-primary";
        $card_psrf_header_cls = "border-primary";
        $card_psrf_text_cls = "text-primary";
    @endphp
    @elseif (!is_null($company_approval_status) && $company_approval_status->$owner_status == 'in process')
    @php
        $card_psrf_cls = "card-primary";
        $card_psrf_header_cls = "border-primary";
        $card_psrf_text_cls = "text-primary";
    @endphp
    @endif
    
    <div class="card card-border {{ $card_psrf_cls }}">
        <div class="card-header {{ $card_psrf_header_cls }} bg-transparent">
            <h3 class="card-title {{ $card_psrf_text_cls }} mb-0">
                {{ $company_information->$company_owner->first_name }} {{ $company_information->$company_owner->last_name }} Background Check Report
            </h3>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    @if (!is_null($company_information->$company_owner->bg_check_document_id))
                    <a data-fancybox="gallery_{{ $document_type }}"
                        href="{{ asset('/uploads/media/'.$company_information->$company_owner->bg_check_report->media->file_name) }}">
                        <i class="far fa-file-pdf font-50"></i>
                    </a>

                    @else
                    <p class="font-15">Not Yet Received</p>
                    <div class="clearfix">&nbsp;</div>
                    <a href="javascript:;" class="btn btn-danger btn-sm waves-effect waves-light uploadFile" data-toggle="modal" data-target="#CompanyFilesModal" data-expiry="no" data-document_type="{{ $document_type }}" data-field_name="bg_check_document_id"><i class="fas fa-upload"></i> Upload</a>
                    @endif
                </div>
                
                <div class="col-md-3 text-center">
                    @php
                        $pre_screen_questions = \App\Models\CompanyOwnerPreScreenQuestion::where([
                            ['company_id', $company_item->id],
                            ['company_user_id', $company_information->$company_owner->id]
                        ])->latest()->first();
                    @endphp


                    @if (!is_null($pre_screen_questions) && !is_null($pre_screen_questions->bg_check_pdf))
                    
                    <a data-fancybox="gallery_{{ $document_type }}" href="{{ asset('/uploads/media/'.$pre_screen_questions->bg_check_pdf->file_name) }}" data-toggle="tooltip" data-placement="bottom" data-original-title="BG Check Question File">
                        <i class="far fa-file-pdf font-50"></i>
                    </a>
                    <?php /* <br />
                    BG Check Question File  */ ?>
                    @endif
                </div>
                
                <div class="col-md-3 text-center">
                    @if (!is_null($pre_screen_questions) && !is_null($pre_screen_questions->pre_screen_question_file))
                    
                    <a data-fancybox="gallery_{{ $document_type }}" href="{{ asset('/uploads/media/'.$pre_screen_questions->pre_screen_question_file->file_name) }}" data-toggle="tooltip" data-placement="bottom" data-original-title="Pre Screen Question File">
                        <i class="far fa-file-pdf font-50"></i>
                    </a>
                    <?php /* <br />
                    Pre Screen Question File */ ?>
                    @endif
                </div>
            </div>
        </div>

        @if (!is_null($company_information->$company_owner->bg_check_document_id))
        <div class="card-footer bg-transparent border-0">
            <div class="row">
                <div class="col-md-6">
                    <a class="btn btn-primary btn-sm"  href="{{ asset('/uploads/media/'.$company_information->$company_owner->bg_check_report->media->file_name) }}" download> <i class="far fa-file-pdf"></i> &nbsp; Download </a>
                </div>
                <div class="col-md-6 text-right">
                    <a href="javascript:;" class="btn btn-danger btn-sm remove_file" data-document_id="{{ $company_information->$company_owner->bg_check_document_id }}" data-document_type="{{ $document_type }}" data-field_name="bg_check_document_id">Delete</a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endif