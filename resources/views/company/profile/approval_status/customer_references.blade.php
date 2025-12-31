@if ($company_approval_status->customer_references != 'not required')
<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->customer_references) }}">   
    <a href="javascript:;" data-toggle="modal" data-target="#customerReferenceModal">Customer References</a>
    {!! $company_approval_status->showStatusIcon($company_approval_status->customer_references) !!}
</li>


<div class="modal fade" id="customerReferenceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Customer References</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                @if ($company_approval_status->customer_references == 'pending')
                <p class="font-15">We have not received your Customer References Form. Please upload a completed references form.</p>
                @elseif ($company_approval_status->customer_references == 'in process')
                <p class="font-15">Your customer references form is pending validation. Thank you for uploading</p>
                @elseif ($company_approval_status->customer_references == 'completed')
                <p class="font-15 font-bold">We've received your customer references.</p>
                @endif
                
                <div class="clearfix">&nbsp;</div>
                <h5>Thank You!</h5>
                
                @if ($company_approval_status->customer_references == 'pending')
                <div class="clearfix">&nbsp;</div>
                <div class="btn-group btn-group-solid">
                    <a href="{{ url('account/application/customer-references-download') }}" class="btn btn-success btn-sm" download>Download A Copy</a>
                    <a href="" class="btn btn-primary btn-sm close_current_modal" data-toggle="modal" data-target="#customerReferenceFormModal" download>Upload Customer References</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if ($company_approval_status->customer_references == 'pending')
<div class="modal fade" id="customerReferenceFormModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Customer References</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('upload-company-document'), 'class' => 'module_form upload_document_form', 'files' => true]) !!}
            {!! Form::hidden('document_type', 'customer_references') !!}
            {!! Form::hidden('file_field_name', 'customer_references_file_id') !!}
            <div class="modal-body text-left">
                <p class="text-center font-15">We have not received your Customer References Form. Please upload a completed references form.</p>
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
@endif