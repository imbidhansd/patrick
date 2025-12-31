<?php
$admin_page_title = 'Company Owners';
?>
@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="row">
    <div class="col-lg-9 col-md-8 col-sm-12">

        @if (isset($company_item) && !is_null($company_item) && $company_item->number_of_owners > 1)
        <div class="row">
            @for($num = 1; $num <= $company_item->number_of_owners; $num++)

            @php
                $name_field = 'company_owner_' . $num . '_full_name';
                $email_field = 'company_owner_' . $num . '_email';
                $phone_field = 'company_owner_' . $num . '_phone';
                $invitation_key_field = 'company_owner_' . $num . '_invitation_key';
                $status_field = 'company_owner_' . $num . '_status';

                $company_owner = 'company_owner'.$num;

                $company_user_information = $company_information->$company_owner;
            @endphp


            @if (isset($company_information->$email_field) && $company_information->$email_field != '')
            <div class="col-md-6">
                <div class="text-center card-box">
                    <div class="member-card">
                        <div class="avatar-xl member-thumb mx-auto">
                            @if (!is_null($company_user_information) && !is_null($company_user_information->media_id))
                            <img src="{{ asset('/uploads/media/fit_thumbs/80x80/'.$company_user_information->media->file_name) }}" class="rounded-circle img-thumbnail" alt="profile-image" />
                            @else
                            <i class="fas fa-user-circle font-80 text-dark rounded-circle img-thumbnail"></i>
                            @endif

                            @if (!is_null($company_user_information) && $company_user_information->company_user_type == 'company_super_admin')
                            <i class="mdi mdi-star-circle member-star text-success" title="Company Super Admin"></i>
                            @endif
                        </div>

                        <div class="mt-3">
                            <h4 class="font-18 mb-1">{{ $company_information->$name_field }}</h4>
                            <p class="text-muted">
                                @if (!is_null($company_user_information))
                                {{ '@'.$company_user_information->username }}
                                <span> | </span>
                                @endif
                                <span class="text-pink">{{ $company_information->$email_field }} </span>
                            </p>
                        </div>

                        @if (!is_null($company_user_information))
                        <p class="text-muted">
                            {!! substr($company_user_information->user_bio, 0, 200) !!}
                        </p>
                        @endif

                        @if ($company_information->$status_field == 'pending')
                            <button type="button" class="btn btn-primary mt-3 btn-rounded btn-bordered waves-effect width-md waves-light f_btn_round float-left" data-toggle="modal" data-target="#editOwnerModal{{ $num }}">Edit</button>

                            <div class="modal fade" id="editOwnerModal{{ $num }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content ">
                                        <div class="modal-header text-center">
                                            <h4 class="modal-title w-100 font-weight-bold text-left">Update Company Owner {{ $company_information->$name_field }}</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        {!! Form::open(['url' => 'update-company-profile', 'class' => 'module_form update_company_profile_form']) !!}

                                        {!! Form::hidden('update_type', 'company_owner_info') !!}
                                        <div class="modal-body text-left">
                                            <div class="form-group">
                                                {!! Form::label('Owner '.$num.' Firstname Lastname') !!}
                                                {!! Form::text('company_owner_'.$num.'_full_name', $company_information->$name_field, ['class' => 'form-control', 'required' => true, 'maxlength' => 255]) !!}
                                            </div>

                                            <div class="form-group">
                                                {!! Form::label('Owner '.$num.' Email Address') !!}
                                                {!! Form::email('company_owner_'.$num.'_email', $company_information->$email_field, ['class' => 'form-control', 'required' => true, 'maxlength' => 255]) !!}
                                            </div>
                                            
                                            <div class="form-group">
                                                {!! Form::label('Owner '.$num.' Phone') !!}
                                                {!! Form::text('company_owner_'.$num.'_phone', $company_information->$phone_field, ['class' => 'form-control', 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000', 'required' => true]) !!}
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary waves-effect waves-light update_company_profile_btn">Save changes</button>
                                            <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                            

                            {!! Form::open(['url' => url('invite-company-owner'), 'class' => 'invite_company_user_form float-right']) !!}
                            {!! Form::hidden('owner_num', $num) !!}
                            <button data-name="{{ $company_information->$name_field }}" data-email="{{ $company_information->$email_field }}" type="submit" class="btn btn-primary mt-3 btn-rounded btn-bordered waves-effect width-md waves-light f_btn_round invitation_btn">Send Invitation</button>
                            {!! Form::close() !!}
                        @elseif ($company_information->$status_field == 'invited')
                            <button class="btn btn-info mt-3 btn-rounded btn-bordered waves-effect width-md waves-light f_btn_round float-left">Invited</button>
                            
                            {!! Form::open(['url' => url('invite-company-owner'), 'class' => 'invite_company_user_form float-right']) !!}
                            {!! Form::hidden('owner_num', $num) !!}
                            <button data-name="{{ $company_information->$name_field }}" data-email="{{ $company_information->$email_field }}" type="submit" class="btn btn-primary mt-3 btn-rounded btn-bordered waves-effect width-md waves-light f_btn_round invitation_btn">Resend Invitation</button>
                            {!! Form::close() !!}
                        @elseif ($company_information->$status_field == 'registered')
                            <button class="btn btn-success mt-3 btn-rounded btn-bordered waves-effect width-md waves-light f_btn_round">Registered</button>
                        @endif
                    </div>
                </div>
            </div>
            @endif


            @endfor
        </div>
        @endif


    </div>

    @include('company.profile._company_profile_sidebar')
</div>
@endsection


@section ('page_js')
@stack('company_document_approval_status_js')
<!-- Plugins js -->
<script src="{{ asset('/themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>

<!-- Init js-->
<script src="{{ asset('/themes/admin/assets/js/pages/form-masks.init.js') }}"></script>
<script type="text/javascript">
    $(function () {
        $('.invitation_btn').click(function () {
            var btn = $(this);

            var name = btn.data('name');
            var email = btn.data('email');
            var html = "<h4 class='mt-0 mb-0'>Are you sure to invite?</h4><br/>Name: " + name + "<br/>Email: " + email + "<br/><br/><small class='text-danger'><strong>Note:</strong> Once an owner invited, you can't change name/email for this owner!</small>"


            Swal.fire({
                text: "Are you sure to invite?",
                html: html,
                type: "question",
                showCancelButton: !0,
                confirmButtonColor: "#53479a",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, Invite!"
            }).then(function (t) {
                if (typeof t.value !== 'undefined') {
                    btn.closest('.invite_company_user_form').submit();
                }
            });

            return false;
        });
        
        
        $('.update_company_profile_form').submit(function () {
            var instance = $(this).parsley();
            if (instance.isValid()){
                $(".update_company_profile_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
                $(".update_company_profile_btn").attr("disabled", true);
            } else {
                $(".update_company_profile_btn").html('Submit');
                $(".update_company_profile_btn").attr("disabled", false);
            }
        });
    });
</script>
@endsection
