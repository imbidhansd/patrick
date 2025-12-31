<div class="modal fade" id="acceptCompanyDocumentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">
                    Accept <span class="modal-title-text"></span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => '', 'class' => 'module_form', 'id' => 'company_document_accept_form']) !!}
            {!! Form::hidden('approval_status', 'completed', ['required' => true]) !!}
            {!! Form::hidden('company_id', $company_item->id, ['required' => true]) !!}
            {!! Form::hidden('document_type', null, ['id' => 'document_type_hh', 'required' => true]) !!}
            {!! Form::hidden('document_id', null, ['id' => 'document_id_hh', 'required' => true]) !!}
            
            <div class="modal-body">
                <div class="text-center">
                    <h3>Are you sure?</h3>
                    <p>You won't be able to revert this!</p>
                </div>
                
                <div class="form-group" id="expiry_date_field" style="display: none;">
                    {!! Form::label('Expiry Date') !!}
                    {!! Form::text('expiration_date', null, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'MM/DD/YYYY', 'data-toggle' => 'input-mask', 'data-mask-format' => '00/00/0000', 'required' => false]) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light" id="submit_btn">Submit</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


<div class="modal fade" id="rejectCompanyDocumentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">
                    Reason for Reject <span class="modal-title-text"></span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => '', 'class' => 'module_form', 'id' => 'company_document_reject_form']) !!}
            {!! Form::hidden('approval_status', 'rejected', ['required' => true]) !!}
            {!! Form::hidden('company_id', $company_item->id, ['required' => true]) !!}
            {!! Form::hidden('document_type', null, ['id' => 'document_type_hh', 'required' => true]) !!}
            {!! Form::hidden('document_id', null, ['id' => 'document_id_hh', 'required' => true]) !!}
            
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('Note') !!}
                    {!! Form::textarea('reject_note', null, ['class' => 'form-control', 'required' => true]) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light" id="submit_btn">Submit</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


{!! Form::open(['url' => url('admin/companies/remove-company-documents'), 'class' => 'module_form', 'id' => 'remove_company_document_form']) !!}
{!! Form::hidden('company_id', $company_item->id, ['required' => true]) !!}
{!! Form::hidden('document_type', null, ['id' => 'document_type_h', 'required' => true]) !!}
{!! Form::hidden('field_name', null, ['id' => 'field_name_h', 'required' => true]) !!}
{!! Form::hidden('document_id', null, ['id' => 'document_id_h', 'required' => true]) !!}
{!! Form::close() !!}