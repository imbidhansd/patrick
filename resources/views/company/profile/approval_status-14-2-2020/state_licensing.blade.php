@if ($company_approval_status->state_licensing != 'not required')
<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->state_licensing) }}">

    @if ($company_approval_status->state_licensing == 'pending')
        <a href="javascript:;" data-toggle="modal" data-target="#stateLicensingModal">State Licensing</a>
    @elseif ($company_approval_status->state_licensing == 'completed')
        <a href="javascript:;" data-toggle="modal" data-target="#stateLicensingModal">State Licensing</a>
    @else
        State Licensing
    @endif
    {!! $company_approval_status->showStatusIcon($company_approval_status->state_licensing)
    !!}
</li>

@if ($company_approval_status->state_licensing == 'pending' || $company_approval_status->state_licensing == 'completed')
<div class="modal fade" id="stateLicensingModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">State Licensing</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            @if ($company_approval_status->state_licensing == 'pending')
                {!! Form::open(['url' => url('upload-company-document'), 'class' => 'module_form', 'files' => true]) !!}
                {!! Form::hidden('document_type', 'state_licensing') !!}
                {!! Form::hidden('file_field_name', 'state_licensed_file_id') !!}
                <div class="modal-body text-left">

                    <div class="form-group">
                        {!! Form::label('Document File') !!}
                        {!! Form::file('file', ['class' => 'filestyle', 'accept' => 'application/pdf', 'required' => true])
                        !!}
                    </div>

                    <div class="text-center text-danger"> Need help? Call Member Support at <a href="tel: 720-445-4400"
                            class="text-info"><strong>720-445-4400</strong></a></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">Upload File</button>
                </div>
                {!! Form::close() !!}
            @elseif ($company_approval_status->state_licensing == 'completed')
                <div class="modal-body text-center">
                    <h4>Your State Licensing File has been received!</h4>
                    <div class="clearfix">&nbsp;</div>
                    <h5>Thank You!</h5>
                </div>
            @endif
        </div>
    </div>
</div>
@endif
@endif
