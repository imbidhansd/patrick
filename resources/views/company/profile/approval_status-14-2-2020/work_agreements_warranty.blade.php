@if ($company_approval_status->work_agreements_warranty != 'not required')
<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->work_agreements_warranty) }}">

    @if ($company_approval_status->work_agreements_warranty == 'pending')
        <a href="javascript:;" data-toggle="modal" data-target="#workAgreementsWarrantyModal">Work Agreements Warranty</a>
    @elseif ($company_approval_status->work_agreements_warranty == 'completed')
        <a href="javascript:;" data-toggle="modal" data-target="#workAgreementsWarrantyModal">Work Agreements Warranty</a>
    @else
        Work Agreements Warranty
    @endif
    {!! $company_approval_status->showStatusIcon($company_approval_status->work_agreements_warranty)
    !!}
</li>

@if ($company_approval_status->work_agreements_warranty == 'pending' || $company_approval_status->work_agreements_warranty == 'completed')
<div class="modal fade" id="workAgreementsWarrantyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Work Agreements Warranty</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            @if ($company_approval_status->work_agreements_warranty == 'pending')
                {!! Form::open(['url' => url('upload-company-document'), 'class' => 'module_form', 'files' => true]) !!}
                {!! Form::hidden('document_type', 'work_agreements_warranty') !!}
                {!! Form::hidden('file_field_name', 'written_warrenty_file_id') !!}
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
            @elseif ($company_approval_status->work_agreements_warranty == 'completed')
                <div class="modal-body text-center">
                    <h4>Your Work Agreements Warranty File has been received!</h4>
                    <div class="clearfix">&nbsp;</div>
                    <h5>Thank You!</h5>
                </div>
            @endif
        </div>
    </div>
</div>
@endif
@endif
