@if ($company_approval_status->registered_legally_to_state != 'not required')
<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->registered_legally_to_state) }}">
    
    @if ($company_approval_status->registered_legally_to_state == 'pending')
    <a href="javascript:;" data-toggle="modal" data-target="#registeredLegallyToStateModal">State Business Registration</a>
    @elseif ($company_approval_status->registered_legally_to_state == 'in process')
    <a href="javascript:;" data-toggle="modal" data-target="#registeredLegallyToStateStatusModal">State Business Registration</a>
    @elseif ($company_approval_status->registered_legally_to_state == 'completed')
    <a href="javascript:;" data-toggle="modal" data-target="#registeredLegallyToStateStatusModal">State Business Registration</a>
    @else
    Registered Legally To State
    @endif
    
    {!! $company_approval_status->showStatusIcon($company_approval_status->registered_legally_to_state)
    !!}
</li>

@if ($company_approval_status->registered_legally_to_state == 'pending' || $company_approval_status->registered_legally_to_state == 'in process')
<div class="modal fade" id="registeredLegallyToStateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">State Business Registration</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('upload-company-document'), 'class' => 'module_form upload_document_form', 'files' => true]) !!}
            {!! Form::hidden('document_type', 'registered_legally_to_state') !!}
            {!! Form::hidden('file_field_name', 'state_business_registeration_file_id') !!}
            <div class="modal-body text-left">
                <div class="text-center">
                    <p class="font-15 font-bold">We have not received a copy of your company's state business registration file.</p>
                    <p class="font-15">Please upload a copy as soon as possible.</p>
                    <p class="font-15">A state business registration file can be a copy of your articles of incorporation or a good standing document from your secretary of state.Some kind of document proving your company name has been registered and is recognized by the state you live in.</p>
                    <h5>Thank you!</h5>
                </div>
                <div class="form-group">
                    {!! Form::label('Document File') !!}
                    {!! Form::file('file', ['class' => 'filestyle', 'accept' => 'image/*,.doc,.docx,.pdf', 'required' => true])
                    !!}
                </div>

                <div class="text-center text-danger"> Need help? Call Member Support at <a href="tel: 720-445-4400"
                        class="text-info"><strong>720-445-4400</strong></a></div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light upload_document_btn">Upload File</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endif


@if ($company_approval_status->registered_legally_to_state == 'in process' || $company_approval_status->registered_legally_to_state == 'completed')
<div class="modal fade" id="registeredLegallyToStateStatusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">State Business Registration</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                @if ($company_approval_status->registered_legally_to_state == 'in process')
                <p class="font-15">Your state business registration is pending validation.</p>
                <h5>Thank you for uploading</h5>
                @elseif ($company_approval_status->registered_legally_to_state == 'completed')
                <p class="font-15 font-bold">We have received a copy of your company's state business registration file.</p>
                <h5>Thank you!</h5>
                @endif
                
                <div class="btn-group btn-group-solid">
                    @if (!is_null($company_licensing->state_business_registeration_file_id))
                    <a class="btn btn-success btn-sm" href="{{ route('secure.file.company', ['path' => 'media/'.$company_licensing->state_business_registeration_file->media->file_name]) }}" download> <i class="far fa-file-pdf"></i> &nbsp; Download </a>
                    @endif

                    @if ($company_approval_status->registered_legally_to_state == 'in process')
                    <a href="javascript:;" class="btn btn-primary btn-sm close_current_modal" data-toggle="modal" data-target="#registeredLegallyToStateModal">Upload Another File</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endif
