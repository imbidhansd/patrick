@if ($company_approval_status->proof_of_ownership != 'not required')
<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->proof_of_ownership) }}">

    @if ($company_approval_status->proof_of_ownership == 'pending')
    <a href="javascript:;" data-toggle="modal" data-target="#proofOfOwnershipModal">Proof Of Ownership</a>
    @elseif ($company_approval_status->proof_of_ownership == 'in process')
    <a href="javascript:;" data-toggle="modal" data-target="#proofOfOwnershipStatusModal">Proof Of Ownership</a>
    @elseif ($company_approval_status->proof_of_ownership == 'completed')
    <a href="javascript:;" data-toggle="modal" data-target="#proofOfOwnershipStatusModal">Proof Of Ownership</a>
    @else
    Proof Of Ownership
    @endif

    {!! $company_approval_status->showStatusIcon($company_approval_status->proof_of_ownership)
    !!}
</li>

@if ($company_approval_status->proof_of_ownership == 'pending' || $company_approval_status->proof_of_ownership == 'in process')
<div class="modal fade" id="proofOfOwnershipModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Proof Of Ownership</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('upload-company-document'), 'class' => 'module_form upload_document_form', 'files' => true]) !!}
            {!! Form::hidden('document_type', 'proof_of_ownership') !!}
            {!! Form::hidden('file_field_name', 'proof_of_ownership_file_id') !!}
            <div class="modal-body text-left">

                <div class="form-group">
                    {!! Form::label('Document File') !!}
                    {!! Form::file('file', ['class' => 'filestyle', 'accept' => 'image/*,.doc,.docx,.pdf', 'required' => true])
                    !!}
                </div>

                <div class="text-center text-danger"> Need help? Call Member Support at <a href="tel: 720-445-4400" class="text-info"><strong>720-445-4400</strong></a></div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light upload_document_btn">Upload File</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endif



@if ($company_approval_status->proof_of_ownership == 'in process' || $company_approval_status->proof_of_ownership == 'completed')
<div class="modal fade" id="proofOfOwnershipStatusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Proof Of Ownership</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <h4>We have received a copy of Proof Of Ownership.</h4>
                @if ($company_approval_status->proof_of_ownership == 'in process')
                <p>
                    <b>Approval Status: </b>
                    <span class="badge badge-info">Pending</span>
                </p>
                @endif
                <h5>Thank You!</h5>

                <div class="btn-group btn-group-solid">
                    @if (!is_null($company_licensing->proof_of_ownership_file_id))
                    <a class="btn btn-success btn-sm" href="{{ route('secure.file.company', ['path' => 'media/'.$company_licensing->proof_of_ownership_file->media->file_name]) }}" download> <i class="far fa-file-pdf"></i> &nbsp; Download </a>
                    @endif

                    @if ($company_approval_status->proof_of_ownership == 'in process')
                    <a href="javascript:;" class="btn btn-primary btn-sm close_current_modal" data-toggle="modal" data-target="#proofOfOwnershipModal">Upload Another File</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endif