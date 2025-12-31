@if ($company_approval_status->registered_legally_to_state != 'not required')
<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->registered_legally_to_state) }}">
    @if ($company_approval_status->registered_legally_to_state == 'pending')
    <a href="javascript:;" data-toggle="modal" data-target="#registeredLegallyToStateModal">Registered Legally To
        State</a>
    @else
    Registered Legally To State
    @endif
    {!! $company_approval_status->showStatusIcon($company_approval_status->registered_legally_to_state)
    !!}
</li>

@if ($company_approval_status->registered_legally_to_state == 'pending')
<div class="modal fade" id="registeredLegallyToStateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Registered Legally To
                    State</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('upload-company-document'), 'class' => 'module_form', 'files' => true]) !!}
            {!! Form::hidden('document_type', 'registered_legally_to_state') !!}
            {!! Form::hidden('file_field_name', 'state_business_registeration_file_id') !!}
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
        </div>
    </div>
</div>
@endif
@endif
