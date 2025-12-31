<?php
    $admin_page_title = 'Company Profile';
?>
@extends('company.layout')


@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="row">

    <div class="col-sm-9">

        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title text-white mb-0">User Information <span
                                class="badge badge-warning">Private</span></h3>
                    </div>
                    <div class="card-body">
                        <div class="text-left">
                            <p class="text-muted "><strong>Username:</strong>
                                <span>{{ $company_detail->username }}</span></p>

                            <p class="text-muted "><strong>User Email:</strong>
                                <span>{{ $company_detail->email }}</span></p>

                            <p class="text-muted ">
                                <strong>Password:</strong>
                                <span>
                                    &nbsp;

                                    <a href="{{ url('profile') }}"
                                        class="btn btn-sm btn-primary btn-rounded width-md waves-effect waves-light">Update
                                        Password</a>
                                </span>
                            </p>

                            <p class="text-muted "><strong>Approval Date:</strong> <span class="badge badge-danger">Not
                                    An Approved
                                    Company</span></p>

                            <p class="text-muted "><strong>Renewal Date:</strong> <span class="badge badge-danger">Not
                                    An Approved
                                    Company</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <div class="card-widgets">
                            <a href="javascript:;" title="Edit Company Imformation" data-toggle="modal"
                                data-target="#udpateCompanyInfoModal"><i class="fas fa-edit"></i>
                                Edit</a>
                        </div>
                        <h3 class="card-title text-white mb-0">Company Contact Information <b
                                class="badge badge-dark">Public</b></h3>
                    </div>
                    <div class="card-body">
                        <div class="text-left">
                            <p class="text-muted "><strong>Company Name:</strong>
                                <span>{{ $company_detail->company_name }}</span></p>

                            <p class="text-muted "><strong>Main Company Phone:</strong>
                                <span>{{ $company_detail->main_company_telephone }}</span></p>

                            <p class="text-muted "><strong>Secondary Company Phone:</strong>
                                <span>{{ $company_detail->secondary_telephone }}</span></p>

                            <p class="text-muted "><strong>Company Website:</strong>
                                <span>{{ $company_detail->company_website }}</span></p>

                            <p class="text-muted "><strong>Company Mailing Address:</strong>
                                <span>{{ $company_detail->company_mailing_address.', '.$company_detail->suite.', '.$company_detail->city.', '.$company_detail->state->name.'- '.$company_detail->zipcode }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="card-widgets">
                            <a href="#" title="Edit Company Owner Imformation" data-toggle="modal"
                                data-target="#udpateCompanyOwnerModal"><i class="fas fa-edit"></i> Edit</a>
                        </div>
                        <h3 class="card-title text-white mb-0">Company Owner Information <b
                                class="badge badge-warning">Private</b></h3>
                    </div>
                    <div class="card-body">
                        <div class="text-left">
                            <p class="text-muted "><strong>Owner 1 First and Last Name:</strong>
                                <span>{{ $company_detail->owner_name }}</span></p>
                            <p class="text-muted "><strong>Owner 1 Email Address:</strong>
                                <span>{{ $company_detail->owner_email }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="card-widgets">
                            <a href="#" title="Edit Internal Contact Imformation" data-toggle="modal"
                                data-target="#udpateInternalContactModal"><i class="fas fa-edit"></i> Edit</a>
                        </div>
                        <h3 class="card-title text-white mb-0">Internal Contact Information <b
                                class="badge badge-warning">Private</b></h3>
                    </div>
                    <div class="card-body">
                        <div class="text-left">
                            <p class="text-muted "><strong>Internal Contact First & Last Name:</strong>
                                <span>{{ $company_detail->internal_contact_name }}</span></p>

                            <p class="text-muted "><strong>Internal Contact Email:</strong>
                                <span>{{ $company_detail->internal_contact_email }}</span></p>

                            <p class="text-muted "><strong>Internal Contact Phone:</strong>
                                <span>{{ $company_detail->internal_contact_phone }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <div class="card-widgets">
                            <a href="#" title="Edit Company Bio Imformation" data-toggle="modal"
                                data-target="#udpateCompanyBioModal"><i class="fas fa-edit"></i> Edit</a>
                        </div>
                        <h3 class="card-title text-white mb-0">Company Bio <b class="badge badge-dark">Public</b>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="text-left">
                            <p class="text-muted ">{!! $company_detail->company_bio !!}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header bg-info">
                        <h3 class="card-title text-white mb-0">Company Logo <b class="badge badge-dark">Public</b>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                @if (!is_null($company_detail->company_logo))
                                <a href="{{ asset('/') }}uploads/media/{{ $company_detail->company_logo->file_name }}"
                                    data-fancybox="gallery">
                                    <img src="{{ asset('/') }}/uploads/media/fit_thumbs/100x100/{{ $company_detail->company_logo->file_name }}"
                                        class='img-thumbnail' />
                                </a>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                @include('company.profile._company_logo_upload')
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <div class="card-widgets">
                            <a href="#" title="Edit Professional Affiliations Imformation"><i
                                    class="fas fa-pen-fancy"></i></a>
                        </div>
                        <h3 class="card-title text-white mb-0">Professional Affiliations <b>Public</b></h3>
                    </div>
                    <div class="card-body">
                        <div class="text-left">
                            <p class="text-muted "><strong>Professional Affiliations Here:</strong> <span></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('company.profile._company_profile_sidebar')
</div>



<div class="modal fade" id="udpateCompanyInfoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Update Company Contact Info</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('update-company-profile'), 'class' => 'module_form ']) !!}

            {!! Form::hidden('update_type', 'company_contact_info') !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('Main Company Telephone') !!}
                            {!! Form::text('main_company_telephone', $company_detail->main_company_telephone, ['class'
                            => 'form-control', 'required' => true, 'maxlength' => 255, 'data-toggle' => 'input-mask',
                            'data-mask-format' => '(000) 000-0000']) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('Secondary Telephone') !!}
                            {!! Form::text('secondary_telephone', $company_detail->secondary_telephone, ['class' =>
                            'form-control', 'required' => false, 'maxlength' => 255, 'data-toggle' => 'input-mask',
                            'data-mask-format' => '(000) 000-0000']) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('Company Website') !!}
                            {!! Form::text('company_website', $company_detail->company_website, ['class' =>
                            'form-control', 'placeholder' => '', 'required' => true, 'maxlength' => 255]) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('H.No./Street') !!}
                            {!! Form::text('company_mailing_address', $company_detail->company_mailing_address, ['class'
                            => 'form-control',
                            'required' => true, 'maxlength' => 255]) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('Suite') !!}
                            {!! Form::text('suite', $company_detail->suite, ['class' => 'form-control',
                            'required' => false, 'maxlength' => 255]) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('City') !!}
                            {!! Form::text('city', $company_detail->city, ['class' => 'form-control',
                            'required' => false, 'maxlength' => 255]) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('State') !!}
                            {!! Form::select('state_id', $states, $company_detail->state_id, ['class' => 'form-control',
                            'required' => true, 'maxlength' => 255]) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('Zipcode') !!}
                            {!! Form::text('zipcode', $company_detail->zipcode, ['class' => 'form-control', 'required'
                            => true, 'maxlength' => 5, 'data-toggle' => 'input-mask', 'data-mask-format' => '00000'])
                            !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Save changes</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


<div class="modal fade" id="udpateCompanyOwnerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Update Company Owner Info</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('update-company-profile'), 'class' => 'module_form ']) !!}

            {!! Form::hidden('update_type', 'company_owner_info') !!}
            <div class="modal-body">

                <div class="form-group">
                    {!! Form::label('Owner 1 Firstname Lastname') !!}
                    {!! Form::text('owner_name', $company_detail->owner_name, ['class' => 'form-control', 'required' =>
                    true, 'maxlength' => 255]) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('Owner 1 Email Address') !!}
                    {!! Form::email('owner_email', $company_detail->owner_email, ['class' => 'form-control', 'required'
                    => true, 'maxlength' => 255]) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Save changes</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="modal fade" id="udpateInternalContactModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Update Internal Contact Info</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('update-company-profile'), 'class' => 'module_form ']) !!}

            {!! Form::hidden('update_type', 'internal_contact_info') !!}
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('Internal Contact Firstname Lastname') !!}
                    {!! Form::text('internal_contact_name', $company_detail->internal_contact_name, ['class' =>
                    'form-control', 'required' => true, 'maxlength' => 255]) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('Internal Contact Email Address') !!}
                    {!! Form::email('internal_contact_email', $company_detail->internal_contact_email, ['class' =>
                    'form-control', 'required' => true, 'maxlength' => 255]) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('Internal Contact Phone') !!}
                    {!! Form::text('internal_contact_phone', $company_detail->internal_contact_phone, ['class' =>
                    'form-control', 'required' => true, 'maxlength' => 255, 'data-toggle' => 'input-mask',
                    'data-mask-format' => '(000) 000-0000']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Save changes</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


<div class="modal fade" id="udpateCompanyBioModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Update Company Contact Info</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('update-company-profile'), 'class' => 'module_form ']) !!}

            {!! Form::hidden('update_type', 'company_bio') !!}
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('Company Bio') !!}
                    {!! Form::textarea('company_bio', $company_detail->company_bio, ['class' => 'form-control
                    summernote', 'required' => false]) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Save changes</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@section ('page_js')
@include('company.profile._js')

<!-- Plugins js -->
<script src="{{ asset('/themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/js/pages/form-masks.init.js') }}"></script>

<!-- Summernote css -->
<link href="{{ asset('/themes/admin/assets/libs/summernote/summernote-bs4.css') }}" rel="stylesheet" type="text/css" />
<!-- Summernote js -->
<script src="{{ asset('/themes/admin/assets/libs/summernote/summernote-bs4.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function (){
        $(".summernote").summernote({
            height: 250,
            minHeight: null,
            maxHeight: null,
            focus: !1,
            toolbar: [
            [ 'style', [ 'style' ] ],
                [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
                [ 'fontname', [ 'fontname' ] ],
                [ 'fontsize', [ 'fontsize' ] ],
                [ 'color', [ 'color' ] ],
                [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
                [ 'table', [ 'table' ] ],
                [ 'insert', [ 'link'] ],
                [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
            ]
        });

        $('#udpateCompanyInfoModal, #udpateCompanyOwnerModal, #udpateCompanyBioModal').on('hidden.bs.modal', function(){
            $(this).find('form')[0].reset();
        });
    });
</script>
@endsection