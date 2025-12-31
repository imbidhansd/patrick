@if ($company_approval_status->country_licensing != 'not required')
<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->country_licensing) }}">

    @if ($company_approval_status->country_licensing == 'pending')
    <a href="javascript:;" data-toggle="modal" data-target="#countryLicensingModal">County Licensing</a>
    @elseif ($company_approval_status->state_licensing == 'in process')
    <a href="javascript:;" data-toggle="modal" data-target="#countryLicensingStatusModal">County Licensing</a>
    @elseif ($company_approval_status->country_licensing == 'completed')
    <a href="javascript:;" data-toggle="modal" data-target="#countryLicensingStatusModal">County Licensing</a>
    @else
    County Licensing
    @endif
    {!! $company_approval_status->showStatusIcon($company_approval_status->country_licensing)
    !!}
</li>

@if ($company_approval_status->country_licensing == 'pending' || $company_approval_status->country_licensing == 'in process')
<div class="modal fade" id="countryLicensingModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">County Licensing</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('upload-company-document'), 'class' => 'module_form upload_document_form', 'files' => true]) !!}
            {!! Form::hidden('document_type', 'country_licensing') !!}
            {!! Form::hidden('file_field_name', 'country_licensed_file_id') !!}
            <div class="modal-body text-left">
                <p class="text-center font-15">Your county licensing has not yet been received. Please upload copies of all county licenses.</p>
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


@if ($company_approval_status->country_licensing == 'in process' || $company_approval_status->country_licensing == 'completed')
<div class="modal fade" id="countryLicensingStatusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">County Licensing</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                @if ($company_approval_status->country_licensing == 'in process')
                <h2>Pending Review</h2>
                <p class="font-15">Your county licenses are being reviewed.</p>
                @else
                <p class="font-15 font-bold">Your county licenses have been uploaded.</p>
                @endif
                
                <div class="clearfix">&nbsp;</div>
                <h5>Thank You!</h5>
                
                <div class="clearfix">&nbsp;</div>
                <div class="btn-group btn-group-solid">
                    @if (!is_null($company_licensing->country_licensed_file_id))
                    <a class="btn btn-success btn-sm" href="{{ route('secure.file.company', ['path' => 'media/'.$company_licensing->country_licensed_file->media->file_name]) }}" download> <i class="far fa-file-pdf"></i> &nbsp; Download </a>
                    @endif

                    @if ($company_approval_status->country_licensing == 'in process')
                    <a href="javascript:;" class="btn btn-primary btn-sm close_current_modal" data-toggle="modal" data-target="#countryLicensingModal">Upload Another File</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endif
