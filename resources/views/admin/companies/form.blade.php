@php $company_item = $formObj; @endphp

<div class="row">
    <div class="col-md-12 text-right">
        <a href="{{ route('sign-in-company', ['company' => $company_item->id]) }}" title="Masquerade Mode" class="btn btn-warning btn-xs" target="_blank"><i class="fas fa-mask"></i></a>
        <div class="clearfix">&nbsp;</div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary py-3 text-white">
                <div class="card-widgets">
                    <a class="accordion_link" data-toggle="collapse" href="#member_status" role="button" aria-expanded="true" aria-controls="member_status"><i class="mdi mdi-minus"></i></a>
                </div>
                <h5 class="card-title mb-0">
                    <a data-toggle="collapse" class="accordion_link text-white" href="#member_status" role="button" aria-expanded="true" aria-controls="member_status"> Member Status </a>
                </h5>
            </div>

            <div id="member_status" class="collapse show">
                <div class="card-body">
                    @include('admin.companies._membership_status', ['admin_form' => true])
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary py-3 text-white">
                <div class="card-widgets">
                    <a class="accordion_link" data-toggle="collapse" href="#company_profile" role="button" aria-expanded="false" aria-controls="company_profile" class="collapsed"><i class="mdi mdi-minus"></i></a>
                </div>
                <h5 class="card-title mb-0 text-white">
                    <a data-toggle="collapse" class="accordion_link text-white" href="#company_profile" role="button" aria-expanded="false" aria-controls="company_profile"> Company Profile </a>
                </h5>
            </div>

            <div id="company_profile" class="collapse">
                <div class="card-body">
                    <div class="row">
                        @include('admin.companies._profile_edit_form', ['admin_form' => true, 'company_item' => $formObj])
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary py-3 text-white">
                <div class="card-widgets">
                    <a class="accordion_link" data-toggle="collapse" href="#company_owners" role="button" aria-expanded="false" aria-controls="company_owners" class="collapsed"><i class="mdi mdi-minus"></i></a>
                </div>
                <h5 class="card-title mb-0 text-white">
                    <a data-toggle="collapse" class="accordion_link text-white" href="#company_owners" role="button" aria-expanded="false" aria-controls="company_owners"> Company Owners </a>
                </h5>
            </div>

            <div id="company_owners" class="collapse">
                <div class="card-body">
                    <div class="row">
                        @include('admin.companies._company_owners')
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary py-3 text-white">
                <div class="card-widgets">
                    <div class="card-widgets">
                        <?php /* @if ($company_item->status == 'Pending Approval') */ ?>
                        <a href="javascript:;" data-toggle="modal" data-target="#EditCompanyDocumentModal" class="btn btn-orange btn-sm">Edit</a>
                        <?php /* @endif */ ?>
                        <a class="accordion_link" data-toggle="collapse" href="#company_documents" role="button" aria-expanded="false" aria-controls="company_documents" class="collapsed"><i class="mdi mdi-minus"></i></a>
                    </div>
                </div>
                <h5 class="card-title mb-0 text-white">
                    <a data-toggle="collapse" class="accordion_link text-white" href="#company_documents" role="button" aria-expanded="false" aria-controls="company_documents"> Company Documents </a>
                </h5>
            </div>

            <div id="company_documents" class="collapse">
                <div class="card-body">
                    @include('admin.companies._company_documents', ['admin_form' => true])
                    
                    
                    
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary py-3 text-white">
                <div class="card-widgets">
                    <a class="accordion_link" data-toggle="collapse" href="#service_categories" role="button" aria-expanded="false" aria-controls="service_categories" class="collapsed"><i class="mdi mdi-minus"></i></a>
                </div>
                <h5 class="card-title mb-0 text-white">
                    <a data-toggle="collapse" class="accordion_link text-white" href="#service_categories" role="button" aria-expanded="false" aria-controls="service_categories"> Service Categories </a>
                </h5>
            </div>

            <div id="service_categories" class="collapse">
                <div class="card-body">
                    @include('admin.companies._service_category_list', ['admin_form' => true])
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary py-3 text-white">
                <div class="card-widgets">
                    <a class="accordion_link" data-toggle="collapse" href="#zipcodes" role="button" aria-expanded="false" aria-controls="zipcodes" class="collapsed"><i class="mdi mdi-minus"></i></a>
                </div>
                <h5 class="card-title mb-0 text-white">
                    <a data-toggle="collapse" class="accordion_link text-white" href="#zipcodes" role="button" aria-expanded="false" aria-controls="zipcodes"> Zipcodes </a>
                </h5>
            </div>

            <div id="zipcodes" class="collapse">
                <div class="card-body">
                    @include('admin.companies._zipcodes', ['admin_form' => true])
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary py-3 text-white">
                <div class="card-widgets">
                    <a class="accordion_link" data-toggle="collapse" href="#lead_management" role="button" aria-expanded="false" aria-controls="lead_management" class="collapsed"><i class="mdi mdi-minus"></i></a>
                </div>
                <h5 class="card-title mb-0 text-white">
                    <a data-toggle="collapse" class="accordion_link text-white" href="#lead_management" role="button" aria-expanded="false" aria-controls="lead_management"> Lead Management </a>
                </h5>
            </div>

            <div id="lead_management" class="collapse">
                <div class="card-body">
                    @include('admin.companies._leads_display', ['admin_form' => true])
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary py-3 text-white">
                <div class="card-widgets">
                    <a data-toggle="collapse" href="#customer_references" role="button" aria-expanded="false" aria-controls="customer_references" class="collapsed"><i class="mdi mdi-minus"></i></a>
                </div>
                <h5 class="card-title mb-0 text-white">
                    <a data-toggle="collapse" class="text-white" href="#customer_references" role="button" aria-expanded="false" aria-controls="customer_references">Customer References And Professional Affiliations</a>
                </h5>
            </div>

            <div id="customer_references" class="collapse">
                <div class="card-body">
                    @include('admin.companies._customer_references')
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary py-3 text-white">
                <div class="card-widgets">
                    <a class="accordion_link" data-toggle="collapse" href="#payment_details" role="button" aria-expanded="false" aria-controls="payment_details" class="collapsed"><i class="mdi mdi-minus"></i></a>
                </div>
                <h5 class="card-title mb-0 text-white">
                    <a data-toggle="collapse" class="accordion_link text-white" href="#payment_details" role="button" aria-expanded="false" aria-controls="payment_details"> Payment Details </a>
                </h5>
            </div>

            <div id="payment_details" class="collapse">
                <div class="card-body">
                    @include('admin.companies._payment_details')
                </div>
            </div>
        </div>

        <div class="card" id="feedback_list">
            <div class="card-header bg-primary py-3 text-white">
                <div class="card-widgets">
                    <a data-toggle="collapse" href="#feedback" role="button" aria-expanded="false" aria-controls="feedback" class="collapsed"><i class="mdi mdi-minus"></i></a>
                </div>
                <h5 class="card-title mb-0 text-white">
                    <a data-toggle="collapse" class="text-white" href="#feedback" role="button" aria-expanded="false" aria-controls="feedback"> Feedback </a>
                </h5>
            </div>

            <div id="feedback" class="collapse">
                <div class="card-body">
                    @include('admin.companies._feedback_list')
                </div>
            </div>
        </div>

        <div class="card" id="complaint_list">
            <div class="card-header bg-primary py-3 text-white">
                <div class="card-widgets">
                    <a data-toggle="collapse" href="#complaints" role="button" aria-expanded="false" aria-controls="complaints" class="collapsed"><i class="mdi mdi-minus"></i></a>
                </div>
                <h5 class="card-title mb-0 text-white">
                    <a data-toggle="collapse" class="text-white" href="#complaints" role="button" aria-expanded="false" aria-controls="complaints"> Complaints </a>
                </h5>
            </div>

            <div id="complaints" class="collapse">
                <div class="card-body">
                    @include('admin.companies._complaint_list')
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary py-3 text-white">
                <div class="card-widgets">
                    <a data-toggle="collapse" href="#company_faqs" role="button" aria-expanded="false" aria-controls="company_faqs" class="collapsed"><i class="mdi mdi-minus"></i></a>
                </div>
                <h5 class="card-title mb-0 text-white">
                    <a data-toggle="collapse" class="text-white" href="#company_faqs" role="button" aria-expanded="false" aria-controls="company_faqs"> Company FAQs </a>
                </h5>
            </div>

            <div id="company_faqs" class="collapse">
                <div class="card-body">
                    @include('admin.companies._company_faqs')
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary py-3 text-white">
                <div class="card-widgets">
                    <div class="card-widgets">
                        <a href="javascript:;" data-toggle="modal" data-target="#addNoteModal" class="btn btn-orange btn-sm">Add Note</a>
                        <a class="accordion_link" data-toggle="collapse" href="#notes" role="button" aria-expanded="false" aria-controls="notes" class="collapsed"><i class="mdi mdi-minus"></i></a>
                    </div>
                </div>
                <h5 class="card-title mb-0 text-white">
                    <a data-toggle="collapse" class="accordion_link text-white" href="#notes" role="button" aria-expanded="false" aria-controls="notes"> Notes </a>
                </h5>
            </div>

            <div id="notes" class="collapse">
                <div class="card-body">
                    @include('admin.companies._company_notes')
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary py-3 text-white">
                <div class="card-widgets">
                    <a data-toggle="collapse" href="#company_gallery" role="button" aria-expanded="false" aria-controls="company_gallery" class="collapsed"><i class="mdi mdi-minus"></i></a>
                </div>
                <h5 class="card-title mb-0 text-white">
                    <a data-toggle="collapse" class="text-white" href="#company_gallery" role="button" aria-expanded="false" aria-controls="company_gallery"> Photo Gallery </a>
                </h5>
            </div>

            <div id="company_gallery" class="collapse">
                <div class="card-body">
                    @include('admin.companies._company_gallery')
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary py-3 text-white">
                <div class="card-widgets">
                    <a data-toggle="collapse" href="#company_status_history" role="button" aria-expanded="false" aria-controls="company_status_history" class="collapsed"><i class="mdi mdi-minus"></i></a>
                </div>
                <h5 class="card-title mb-0 text-white">
                    <a data-toggle="collapse" class="text-white" href="#company_status_history" role="button" aria-expanded="false" aria-controls="company_status_history"> Company Status History </a>
                </h5>
            </div>

            <div id="company_status_history" class="collapse">
                <div class="card-body">
                    @include('admin.companies._company_status_history')
                </div>
            </div>
        </div>

        <div class="modal fade" id="addNoteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content ">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold text-left">Add Note for {{ $formObj->company_name }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    {!! Form::open(['url' => url('admin/companies/add-company-note'), 'class' => 'module_form']) !!}
                    {!! Form::hidden('company_id', $company_item->id) !!}
                    {!! Form::hidden('company_note_id', null, ['id' => 'company_note_id']) !!}

                    <div class="modal-body">
                        <div class="form-group">
                            {!! Form::textarea('notes', null, ['class' => 'form-control summernote', 'id' => 'company_note',
                            'placeholder' => 'Note', 'required' => true]) !!}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save changes</button>
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="EditCompanyDocumentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold text-left">
                            Edit Document
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    {!! Form::open(['url' => 'admin/companies/update-company-document-list', 'class' => 'module_form', 'id' => 'company_document_edit_form']) !!}
                    {!! Form::hidden('company_id', $company_item->id, ['required' => true]) !!}
                    <div class="modal-body">
                        @php
                            $trade_word = 'General';
                            $statusColumnsArr = [
                                'registered_legally_to_state' => 'State Business Registration',
                                'proof_of_ownership' => 'Proof Of Ownership',
                                'state_licensing' => 'State Licensing',
                                'country_licensing' => 'Country Licensing',
                                'city_licensing' => 'City Licensing',
                                'work_agreements_warranty' => 'Product/Service Warranty',
                                'subcontractor_agreement' => 'Subcontractor Agreement',
                                'general_liablity_insurance_file' => $trade_word.' Liability Insurance',
                                'worker_comsensation_insurance_file' => 'Worker Compensation Insurance',
                                'customer_references' => 'Customer References',
                            ];
                            if ($company_item->trade_id == 2){
                                $trade_word = 'Professional';
                                $statusColumnsArr = [
                                    'registered_legally_to_state' => 'State Business Registration',
                                    'proof_of_ownership' => 'Proof Of Ownership',
                                    'state_licensing' => 'State Licensing',
                                    'country_licensing' => 'Country Licensing',
                                    'city_licensing' => 'City Licensing',
                                    'general_liablity_insurance_file' => $trade_word.' Liability Insurance',
                                    'customer_references' => 'Customer References',
                                ];
                            }

                            
                        @endphp

                        @foreach ($statusColumnsArr AS $key => $value)
                            @php
                                $company_approval_status = $company_item->company_approval_status;
                                $checked = true;
                                if ($company_approval_status->$key == 'not required'){
                                    $checked = false;
                                }
                            @endphp
                        <div class="checkbox checkbox-primary checkbox-circle">
                            {!! Form::checkbox($key, 'yes', $checked, ['id' => $key.'_1', 'class' => '']) !!}
                            <label for="{{ $key.'_1' }}">
                                {{ $value }} - {{ ucfirst($company_approval_status->$key) }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary waves-effect waves-light" id="company_document_upload_btn">Submit</button>
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>