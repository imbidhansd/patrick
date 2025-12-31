@if (!is_null($company_insurances))
    @php
        $insurance_type = $company_insurances->general_liability_insurance_and_worker_compensation_insurance;
    
        $card_cls = "card-primary";
        $card_header_cls = "border-primary";
        $card_text_cls = "text-primary";
    @endphp

    @if (!is_null($company_approval_status) && $company_approval_status->insurance_documents == 'completed')
    @php
        $card_cls = "card-primary";
        $card_header_cls = "border-primary";
        $card_text_cls = "text-primary";
    @endphp
    @elseif (!is_null($company_approval_status) && $company_approval_status->insurance_documents == 'in process')
    @php
        $card_cls = "card-primary";
        $card_header_cls = "border-primary";
        $card_text_cls = "text-primary";
    @endphp
    @endif

    @if ($insurance_type == 'Yes')
    <div class="col-md-4">
        <div class="card card-border {{ $card_cls }}">
            <div class="card-header {{ $card_header_cls }} bg-transparent">
                <h3 class="card-title {{ $card_text_cls }} mb-0">Company Insurance Documents</h3>
            </div>

            <div class="card-body">
                @if (!is_null($company_insurances) && !is_null($company_insurances->gen_lia_ins_file_id) && !is_null($company_insurances->work_com_ins_file_id))
                    <a data-fancybox="gallery_insurance_file" href="{{ route('secure.file', ['path' => 'media/'.$company_insurances->liability_insurance_file->media->file_name]) }}">
                        <i class="far fa-file-pdf font-50"></i>
                    </a>
                @else
                Not Yet Received

                {!! Form::hidden('insurance_type', $insurance_type, ['id' => 'insurance_type']) !!}
                <div class="clearfix">&nbsp;</div>
                <a href="javascript:;" class="btn btn-primary btn-sm waves-effect waves-light uploadSingleInsuranceFile" data-toggle="modal" data-target="#CompanyInsuranceSingleFileModal" data-document_type="insurance_documents" data-field_name="insurance_document_id"><i class="fas fa-upload"></i> Upload</a>
                @endif
            </div>

            @if (!is_null($company_approval_status) && !is_null($company_insurances) && !is_null($company_insurances->gen_lia_ins_file_id) && !is_null($company_insurances->work_com_ins_file_id))
            <div class="card-footer bg-transparent border-0">
                <div class="row">
                    <div class="col-md-6">
                        <a class="btn btn-primary btn-sm" href="{{ route('secure.file', ['path' => 'media/'.$company_insurances->liability_insurance_file->media->file_name]) }}" download> <i class="far fa-file-pdf"></i> &nbsp; Download </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="javascript:;" class="btn btn-danger btn-sm remove_file" data-document_id="{{ $company_insurances->gen_lia_ins_file_id }}" data-document_type="insurance_documents" data-field_name="insurance_document_id">Delete</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="CompanyInsuranceSingleFileModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold text-left">
                        Upload <span class="modal-title-text"></span>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                {!! Form::open(['url' => url('admin/companies/upload-company-documents'), 'class' => 'module_form', 'files'
                => true]) !!}
                {!! Form::hidden('document_type', null, ['id' => 'document_type', 'required' => true]) !!}
                {!! Form::hidden('field_name', null, ['id' => 'field_name', 'required' => true]) !!}
                {!! Form::hidden('expiry_type', 'yes', ['id' => 'expiry_type', 'required' => true]) !!}
                {!! Form::hidden('company_id', $company_item->id, ['required' => true]) !!}
                
                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::label('Insurance File') !!}
                        {!! Form::file('media', ['class' => 'filestyle', 'accept' => 'application/pdf', 'required' => true]) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('General Liability Insurance Agent Agency Document Expiry Date') !!}
                        <div class="input-group">
                            {!! Form::text('expiration_date', null, ['class' => 'form-control date_field', 'autocomplete' => 'off', 'placeholder' => 'MM/DD/YYYY', 'required' => true]) !!}
                            <div class="input-group-append">
                                <span class="input-group-text bg-primary text-white b-0"><i
                                        class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('Workers Compensation Insurance Agent Agency Documents Expiry Date') !!}
                        <div class="input-group">
                            {!! Form::text('expiration_date2', null, ['class' => 'form-control date_field', 'autocomplete' => 'off', 'placeholder' => 'MM/DD/YYYY', 'required' => true]) !!}
                            <div class="input-group-append">
                                <span class="input-group-text bg-primary text-white b-0"><i
                                        class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    
    @else
    <div class="col-md-4">
        <div class="card card-border {{ $card_cls }}">
            <div class="card-header {{ $card_header_cls }} bg-transparent">
                <h3 class="card-title {{ $card_text_cls }} mb-0">General Liability Insurance Agent Agency Document</h3>
            </div>

            <div class="card-body">
                @if (!is_null($company_insurances) && !is_null($company_insurances->gen_lia_ins_file_id))
                    <a data-fancybox="gallery_liability_insurance_file" href="{{ route('secure.file', ['path' => 'media/'.$company_insurances->liability_insurance_file->media->file_name]) }}">
                        <i class="far fa-file-pdf font-50"></i>
                    </a>
                @else
                Not Yet Received

                {!! Form::hidden('insurance_type', $insurance_type, ['id' => 'insurance_type']) !!}
                <div class="clearfix">&nbsp;</div>
                <a href="javascript:;" class="btn btn-primary btn-sm waves-effect waves-light uploadInsuranceFile" data-toggle="modal" data-target="#CompanyInsuranceFileModal" data-document_type="insurance_documents" data-field_name="gen_lia_ins_file_id"><i class="fas fa-upload"></i> Upload</a>
                @endif
            </div>

            @if (!is_null($company_approval_status) && !is_null($company_insurances) && !is_null($company_insurances->gen_lia_ins_file_id))
            <div class="card-footer bg-transparent border-0">
                <div class="row">
                    <div class="col-md-6">
                        <a class="btn btn-primary btn-sm" href="{{ route('secure.file', ['path' => 'media/'.$company_insurances->liability_insurance_file->media->file_name]) }}" download> <i class="far fa-file-pdf"></i> &nbsp; Download </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="javascript:;" class="btn btn-danger btn-sm remove_file" data-document_id="{{ $company_insurances->gen_lia_ins_file_id }}" data-document_type="insurance_documents" data-field_name="gen_lia_ins_file_id">Delete</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>


    <div class="col-md-4">
        <div class="card card-border {{ $card_cls }}">
            <div class="card-header {{ $card_header_cls }} bg-transparent">
                <h3 class="card-title {{ $card_text_cls }} mb-0">Workers Compensation Insurance Agent Agency Documents</h3>
            </div>

            <div class="card-body">
                @if (!is_null($company_insurances) && !is_null($company_insurances->work_com_ins_file_id))
                    <a data-fancybox="gallery_compensation_insurance_file" href="{{ route('secure.file', ['path' => 'media/'.$company_insurances->compensation_insurance_file->media->file_name]) }}">
                        <i class="far fa-file-pdf font-50"></i>
                    </a>
                @else
                Not Yet Received

                {!! Form::hidden('insurance_type', $insurance_type, ['id' => 'insurance_type']) !!}
                <div class="clearfix">&nbsp;</div>
                <a href="javascript:;" class="btn btn-primary btn-sm waves-effect waves-light uploadInsuranceFile" data-toggle="modal" data-target="#CompanyInsuranceFileModal" data-document_type="insurance_documents" data-field_name="work_com_ins_file_id"><i class="fas fa-upload"></i> Upload</a>
                @endif
            </div>

            @if (!is_null($company_approval_status) && !is_null($company_insurances) && !is_null($company_insurances->work_com_ins_file_id))
            <div class="card-footer bg-transparent border-0">
                <div class="row">
                    <div class="col-md-6">
                        <a class="btn btn-primary btn-sm" href="{{ route('secure.file', ['path' => 'media/'.$company_insurances->compensation_insurance_file->media->file_name]) }}" download> <i class="far fa-file-pdf"></i> &nbsp; Download </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="javascript:;" class="btn btn-danger btn-sm remove_file" data-document_id="{{ $company_insurances->work_com_ins_file_id }}" data-document_type="insurance_documents" data-field_name="work_com_ins_file_id">Delete</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="CompanyInsuranceFileModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold text-left">
                        Upload <span class="modal-title-text"></span>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                {!! Form::open(['url' => url('admin/companies/upload-company-documents'), 'class' => 'module_form', 'files'
                => true]) !!}
                {!! Form::hidden('document_type', null, ['id' => 'document_type', 'required' => true]) !!}
                {!! Form::hidden('field_name', null, ['id' => 'field_name', 'required' => true]) !!}
                {!! Form::hidden('expiry_type', 'yes', ['id' => 'expiry_type', 'required' => true]) !!}
                {!! Form::hidden('company_id', $company_item->id, ['required' => true]) !!}
                
                <div class="modal-body">
                    <div id="liability_insuranve_document" style="display: none;">
                        <div class="form-group">
                            {!! Form::label('General Liability Insurance Agent Agency File') !!}
                            {!! Form::file('media', ['class' => 'filestyle', 'accept' => 'application/pdf', 'required' => false]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('Expiry Date') !!}
                            <div class="input-group">
                                {!! Form::text('expiration_date', null, ['class' => 'form-control date_field', 'autocomplete' => 'off', 'placeholder' => 'MM/DD/YYYY', 'required' => false]) !!}
                                <div class="input-group-append">
                                    <span class="input-group-text bg-primary text-white b-0"><i
                                            class="mdi mdi-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="compensation_insurance_document" style="display: none;">
                        <div class="form-group">
                            {!! Form::label('Workers Compensation Insurance Agent Agency File') !!}
                            {!! Form::file('media', ['class' => 'filestyle', 'accept' => 'application/pdf', 'required' => false]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('Expiry Date') !!}
                            <div class="input-group">
                                {!! Form::text('expiration_date', null, ['class' => 'form-control date_field', 'autocomplete' => 'off', 'placeholder' => 'MM/DD/YYYY', 'required' => false]) !!}
                                <div class="input-group-append">
                                    <span class="input-group-text bg-primary text-white b-0"><i
                                            class="mdi mdi-calendar"></i></span>
                                </div>
                            </div>
                        </div>    
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    @endif
@endif