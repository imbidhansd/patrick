@if ($company_approval_status->work_agreements_warranty != 'not required')
<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->work_agreements_warranty) }}">

    @if ($company_approval_status->work_agreements_warranty == 'pending')
    <a href="javascript:;" data-toggle="modal" data-target="#workAgreementsWarrantyModal">Product/Service Warranty</a>
    @elseif ($company_approval_status->work_agreements_warranty == 'in process')
    <a href="javascript:;" data-toggle="modal" data-target="#workAgreementsWarrantyStatusModal">Product/Service Warranty</a>
    @elseif ($company_approval_status->work_agreements_warranty == 'completed')
    <a href="javascript:;" data-toggle="modal" data-target="#workAgreementsWarrantyStatusModal">Product/Service Warranty</a>
    @else
    Product/Service Warranty
    @endif
    {!! $company_approval_status->showStatusIcon($company_approval_status->work_agreements_warranty)
    !!}
</li>

@if ($company_approval_status->work_agreements_warranty == 'pending' || $company_approval_status->work_agreements_warranty == 'in process')
<div class="modal fade" id="workAgreementsWarrantyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Product/Service Warranty</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('upload-company-document'), 'class' => 'module_form upload_document_form', 'files' => true]) !!}
            {!! Form::hidden('document_type', 'work_agreements_warranty') !!}
            {!! Form::hidden('file_field_name', 'written_warrenty_file_id') !!}
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



@if ($company_approval_status->work_agreements_warranty == 'in process' || $company_approval_status->work_agreements_warranty == 'completed')
<div class="modal fade" id="workAgreementsWarrantyStatusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Product/Service Warranty</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <h4>Your Product/Service Warranty File has been received!</h4>
                @if ($company_approval_status->work_agreements_warranty == 'in process')
                <p>
                    <b>Approval Status: </b>
                    <span class="badge badge-info">Pending</span>
                </p>
                @endif
                <h5>Thank You!</h5>

                <div class="btn-group btn-group-solid">
                    @if (!is_null($company_licensing->written_warrenty_file_id))
                    <a class="btn btn-success btn-sm" href="{{ route('secure.file.company', ['path' => 'media/'.$company_licensing->written_warrenty_file->media->file_name]) }}" download> <i class="far fa-file-pdf"></i> &nbsp; Download </a>
                    @endif

                    @if ($company_approval_status->work_agreements_warranty == 'in process')
                    <a href="javascript:;" class="btn btn-primary btn-sm close_current_modal" data-toggle="modal" data-target="#workAgreementsWarrantyModal">Upload Another File</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endif
