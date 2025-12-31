@if ($company_approval_status->city_licensing != 'not required')
<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->city_licensing) }}">

    @if ($company_approval_status->city_licensing == 'pending')
    <a href="javascript:;" data-toggle="modal" data-target="#cityLicensingModal">City Licensing</a>
    @elseif ($company_approval_status->city_licensing == 'in process')
    <a href="javascript:;" data-toggle="modal" data-target="#cityLicensingStatusModal">City Licensing</a>
    @elseif ($company_approval_status->city_licensing == 'completed')
    <a href="javascript:;" data-toggle="modal" data-target="#cityLicensingStatusModal">City Licensing</a>
    @else
    City Licensing
    @endif
    {!! $company_approval_status->showStatusIcon($company_approval_status->city_licensing)
    !!}
</li>

@if ($company_approval_status->city_licensing == 'pending' || $company_approval_status->city_licensing == 'in process')
<div class="modal fade" id="cityLicensingModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">City Licensing</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('upload-company-document'), 'class' => 'module_form upload_document_form', 'files' => true]) !!}
            {!! Form::hidden('document_type', 'city_licensing') !!}
            {!! Form::hidden('file_field_name', 'city_licensed_file_id') !!}
            <div class="modal-body text-left">
                <p class="text-center font-15">Your city licensing has not yet been received. Please upload copies of all city licenses.</p>
                <div class="form-group">
                    {!! Form::label('Document File') !!}
                    {!! Form::file('file', ['class' => 'filestyle', 'accept' => 'image/*,.doc,.docx,.pdf', 'required' => true]) !!}
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


@if ($company_approval_status->city_licensing == 'in process' || $company_approval_status->city_licensing == 'completed')
<div class="modal fade" id="cityLicensingStatusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">City Licensing</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                @if ($company_approval_status->city_licensing == 'in process')
                <h2>Pending Review</h2>
                <p class="font-15">Your city licenses are being reviewed.</p>
                @else
                <p class="font-15 font-bold">Your city licenses have been uploaded.</p>
                @endif
                
                <div class="clearfix">&nbsp;</div>
                <h5>Thank You!</h5>
                
                <div class="clearfix">&nbsp;</div>
                <div class="btn-group btn-group-solid">
                    @if (!is_null($company_licensing->city_licensed_file_id))
                    <a class="btn btn-success btn-sm" href="{{ route('secure.file.company', ['path' => 'media/'.$company_licensing->city_licensed_file->media->file_name]) }}" download> <i class="far fa-file-pdf"></i> &nbsp; Download </a>
                    @endif

                    @if ($company_approval_status->city_licensing == 'in process')
                    <a href="javascript:;" class="btn btn-primary btn-sm close_current_modal" data-toggle="modal" data-target="#cityLicensingModal">Upload Another File</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif


@endif
