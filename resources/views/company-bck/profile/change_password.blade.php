<?php
    $admin_page_title = 'Profile';
?>
@extends('company.layout')


@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <div class="row">
                <div class="col-xl-3 col-md-4">
                    <div class="text-center card-box shadow-none border border-secoundary">
                        <div class="member-card company_image_upload">
                            <div class="avatar-xl member-thumb mb-3 mx-auto d-block">
                                @if (!is_null($company_detail->company_logo))
                                <img src="{{ asset('/') }}/uploads/media/fit_thumbs/100x100/{{ $company_detail->company_logo->file_name }}"
                                        class="rounded-circle img-thumbnail" />
                                @endif
                                <?php /* <i class="mdi mdi-star-circle member-star text-success" title="verified user"></i> */ ?>
                                <div class="overlay">
                                    @include('company.profile._company_logo_upload')
                                </div>
                            </div>

                            <div class="">
                                <h5 class="font-18 mb-1">{{ $company_detail->first_name.' '.$company_detail->last_name }}</h5>
                                <p class="text-muted mb-2">{{ '@'.$company_detail->username }}</p>
                            </div>

                            <button type="button" class="btn btn-success btn-sm width-sm waves-effect mt-2 waves-light" data-toggle="modal" data-target="#updateProfileModal">Update Profile</button>
                            
                            <p class="sub-header mt-3">
                                {!! substr($company_detail->company_bio, 0, 200) !!}
                            </p>

                            <hr/>

                            <div class="text-left">
                                <p class="text-muted font-13"><strong>Full Name :</strong> <span>{{ $company_detail->first_name.' '.$company_detail->last_name }}</span></p>

                                <p class="text-muted font-13"><strong>Mobile :</strong><span>{{ $company_detail->main_company_telephone }}</span></p>

                                <p class="text-muted font-13"><strong>Email :</strong> <span>{{ $company_detail->company_mailing_address }}</span></p>

                                <p class="text-muted font-13"><strong>Location :</strong> <span>{{ $company_detail->city }}</span></p>
                            </div>

                            <ul class="social-links list-inline mt-4">
                                <li class="list-inline-item">
                                    <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="Facebook"><i class="fab fa-facebook-f"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="Twitter"><i class="fab fa-twitter"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="Skype"><i class="fab fa-skype"></i></a>
                                </li>
                            </ul>

                        </div>

                    </div>
                    <!-- end card-box -->

                </div>
                <!-- end col -->

                <div class="col-xl-9 col-md-8">
                    <h5 class="header-title">Change Password</h5>

                    {!! Form::open(['url' => url('change-password'), 'class' => 'module_form full_width']) !!}
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Old Password</label>
                                    {!! Form::password('old_password', ['class' => 'form-control', 'required' => 'required', 'id' => 'password', 'maxlength' => 255, 'data-parsley-uppercase' => 1, 'data-parsley-lowercase' => 1, 'data-parsley-number' => 1, 'data-parsley-special' => 1, 'data-parsley-minlength' => 6, 'data-parsley-maxlength' => 50]) !!}
                                </div>    
                            </div>

                            <div class="clearfix">&nbsp;</div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>New Password</label>
                                    {!! Form::password('new_password', ['class' => 'form-control', 'required' => 'required', 'id' => 'new_password', 'maxlength' => 255, 'data-parsley-uppercase' => 1, 'data-parsley-lowercase' => 1, 'data-parsley-number' => 1, 'data-parsley-special' => 1, 'data-parsley-minlength' => 6, 'data-parsley-maxlength' => 50]) !!}
                                </div>    
                            </div>

                            <div class="clearfix">&nbsp;</div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    {!! Form::password('confirm_password', ['class' => 'form-control', 'required' => 'required', 'data-parsley-equalto' => '#new_password']) !!}
                                </div>
                            </div>

                        </div>
                        <div class="text-left">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Save changes</button>
                        </div>
                    {!! Form::close() !!}
                    
                </div>

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="updateProfileModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Update Profile</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('update-profile'), 'class' => 'module_form']) !!}
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('First Name') !!}
                    {!! Form::text('first_name', $company_detail->first_name, ['class' => 'form-control', 'required' => true, 'maxlength' => 255]) !!}
                </div>
            
                <div class="form-group">
                    {!! Form::label('Last Name') !!}
                    {!! Form::text('last_name', $company_detail->last_name, ['class' => 'form-control', 'required' => true, 'maxlength' => 255]) !!}
                </div>
            
                <div class="form-group">
                    {!! Form::label('Username') !!}
                    {!! Form::text('username', $company_detail->username, ['class' => 'form-control', 'required' => true, 'readonly' => true]) !!}
                </div>
            
                <div class="form-group">
                    {!! Form::label('Email') !!}
                    {!! Form::text('email', $company_detail->email, ['class' => 'form-control', 'required' => true, 'readonly' => true]) !!}
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
<script type="text/javascript">
    $(document).ready(function (){
        $('#updateProfileModal, #updatePasswordModal').on('hidden.bs.modal', function(){
            $(this).find('form')[0].reset();
        });
    });
</script>
@endsection
