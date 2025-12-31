<?php
    $admin_page_title = 'Profile';
?>
@extends('company.layout')


@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="card-box">
    <div class="row">
        <div class="col-lg-3 col-md-4">
            <div class="text-center card-box shadow-none border border-secoundary">
                <div class="member-card ">
                    <div class="company_image_upload">
                        <div class="avatar-xl member-thumb mb-3 mx-auto d-block">
                            @if (!is_null($company_user->media_id))
                            <img src="{{ asset('/') }}/uploads/media/fit_thumbs/100x100/{{ $company_user->media->file_name }}"
                                class="rounded-circle img-thumbnail" />
                            @else
                            <i class="fas fa-user-circle def-user-img"></i>
                            @endif

                            <?php /* <i class="mdi mdi-star-circle member-star text-success" title="verified user"></i> */ ?>
                            <div class="overlay" data-toggle="tooltip" data-placement="top" title="Upload Profile Photo">
                                @include('company.profile._company_user_logo_upload')
                            </div>
                        </div>
                    </div>

                    <div class="">
                        <h5 class="font-18 mb-1">{{ $company_user->full_name() }}</h5>
                        <p class="text-muted mb-2">{{ '@'.$company_user->username }}</p>
                    </div>

                    <button type="button" class="btn btn-info btn-sm width-sm waves-effect mt-2 waves-light"
                        data-toggle="modal" data-target="#updateProfileModal">Update Profile</button>

                    <button type="button" class="btn btn-warning btn-sm width-sm waves-effect mt-2 waves-light"
                        data-toggle="modal" data-target="#updatePasswordModal">Change Password</button>

                    <hr />

                    <div class="text-left">
                        <p class="text-muted font-13">
                            <i class="fas fa-user mr-1"></i>
                            <span>{{ $company_user->full_name() }}</span>
                        </p>
                        @if ($company_user->user_telephone != '')
                        <p class="text-muted font-13">
                            <i class="fas fa-phone mr-1"></i>
                            <span>{{ $company_user->user_telephone }}</span>
                        </p>
                        @endif
                        <p class=   "text-muted font-13">
                            <i class="fas fa-at mr-1"></i>
                            <span>{{ $company_user->email }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-md-8">
            <div class="row">
                @php
                    $class_name = 'col-md-8';

                    if ($company_item->number_of_owners == 1){
                        $class_name = 'col-md-12';
                    }
                @endphp

                <div class="{{ $class_name }}">
                    <div class="card card-border card-info">
                        <div class="card-header border-info bg-transparent">
                            <h3 class="card-title text-muted mb-0">User Bio</h3>
                        </div>

                        <div class="card-body">
                            {!! $company_user->user_bio !!}
                        </div>

                        <div class="card-footer text-right">
                            <a href="javascript:;" data-toggle="modal" data-target="#updateUserBioModal" class="btn btn-primary btn-sm">Edit</a>
                        </div>
                    </div>
                </div>

                @if (isset($company_item) && !is_null($company_item) && $company_item->number_of_owners > 1)
                <div class="col-md-4">
                    <div class="card card-border card-teal">
                        <div class="card-header border-teal bg-transparent">
                            <h3 class="card-title text-muted mb-0">Other Company Owners</h3>
                        </div>
                        <div class="card-body">
                            <div class="inbox-widget">
                                @for($num = 2; $num <= $company_item->number_of_owners; $num++)

                                    @php
                                        $name_field = 'company_owner_' . $num . '_full_name';
                                        $email_field = 'company_owner_' . $num . '_email';
                                        $company_owner = 'company_owner'.$num;

                                        $company_information = $company_item->company_information;
                                        $company_user_information = $company_information->$company_owner;
                                    @endphp

                                    @if (isset($company_information->$email_field) && $company_information->$email_field != '')
                                    <div>
                                        <div class="inbox-item">
                                            @if (!is_null($company_user_information) && !is_null($company_user_information->media_id))
                                            <div class="inbox-item-img">
                                                <img src="{{ asset('/uploads/media/fit_thumbs/40x40/'.$company_user_information->media->file_name )}}" class="rounded-circle" alt="{{ $company_information->$name_field }}">
                                            </div>
                                            @else
                                            <div class="inbox-item-img">
                                                <i class="fas fa-user-circle font-40 text-dark"></i>
                                            </div>
                                            @endif

                                            <p class="inbox-item-author">{{ $company_information->$name_field }}</p>
                                            <p class="inbox-item-text">{{ $company_information->$email_field }}</p>
                                        </div>
                                    </div>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="updateProfileModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Update Profile</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('update-profile'), 'class' => 'module_form ']) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('First Name') !!}
                            {!! Form::text('first_name', $company_user->first_name, ['class' => 'form-control', 'required' => true, 'maxlength' => 255]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('Last Name') !!}
                            {!! Form::text('last_name', $company_user->last_name, ['class' => 'form-control', 'required' => true, 'maxlength' => 255]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('Username') !!}
                            {!! Form::text('username', $company_user->username, ['class' => 'form-control', 'required' => true]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('Email') !!}
                            {!! Form::text('email', $company_user->email, ['class' => 'form-control', 'required' => true]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('Phone') !!}
                            {!! Form::text('user_telephone', $company_user->user_telephone, ['class' => 'form-control', 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000', 'required' => true]) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('Address') !!}
                            {!! Form::text('address', $company_user->address, ['class' => 'form-control', 'required' => true, 'id'=> 'autocomplete_address']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('City') !!}
                            {!! Form::text('city', $company_user->city, ['class' => 'form-control', 'required' => true, 'maxlength' => 255, 'id'=> 'autocomplete_city']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('State') !!}
                            {!! Form::select('state_id', $states, $company_user->state_id, ['class' => 'form-control custom-select', 'required' => true, 'id'=> 'autocomplete_state']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('Zipcode') !!}
                            {!! Form::text('zipcode', $company_user->zipcode, ['class' => 'form-control', 'data-toggle' => 'input-mask', 'data-mask-format' => '00000', 'required' => true, 'id'=> 'autocomplete_zipcode']) !!}
                        </div>
                    </div>
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


<div class="modal fade" id="updatePasswordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Change Password</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('change-password'), 'class' => 'module_form', 'id' => 'change_password_form']) !!}
            <div class="modal-body">
                <div class="form-group">
                    <label>Old Password</label>
                    <div class="input-group">
                        {!! Form::password('old_password', ['class' => 'form-control', 'required' => true, 'id' => 'password', 'data-parsley-minlength' => 6, 'data-parsley-maxlength' => 50]) !!}
                        <span class="input-group-append view-password">
                            <button type="button" class="btn btn-info"><i class="fas fa-eye"></i></button>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <div class="input-group">
                        {!! Form::password('new_password', ['class' => 'form-control', 'required' => true, 'id' => 'new_password', 'maxlength' => 255, 'data-parsley-minlength' => 6, 'data-parsley-maxlength' => 50]) !!}
                        <span class="input-group-append view-password">
                            <button type="button" class="btn btn-info"><i class="fas fa-eye"></i></button>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    {!! Form::password('confirm_password', ['class' => 'form-control', 'required' => true, 'data-parsley-equalto' => '#new_password']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light change_password_btn">Save changes</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="modal fade" id="updateUserBioModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Update User Bio</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('update-profile'), 'class' => 'module_form', 'id' => 'update_user_profile_form']) !!}
            {!! Form::hidden('update_type', 'company_user_bio') !!}
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('User Bio') !!}
                    {!! Form::textarea('user_bio', $company_user->user_bio, ['class' => 'form-control summernote', 'required' => true]) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light update_user_profile_btn">Save changes</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
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

<script type="text/javascript">
    function initAutocomplete() {
        let address1Field = document.querySelector("#autocomplete_address");                       
        autocomplete = new google.maps.places.Autocomplete(address1Field, {
            componentRestrictions: { country: ["us"] },
            fields: ["address_components"],
            types: ["address"],
        });
        address1Field.focus();          
        autocomplete.addListener("place_changed", fillInAddress);
    }
    function fillInAddress() {
        const place = autocomplete.getPlace(); 

        for (const component of place.address_components) {
            const componentType = component.types[0];
            switch (componentType) {    
                case "postal_code": {                            
                    document.querySelector("#autocomplete_zipcode").value = component.long_name;
                    break;
                }                
                case "locality": {
                    document.querySelector("#autocomplete_city").value = component.long_name;
                    break;
                }                        
                case "administrative_area_level_1": {
                    selectedState = component.long_name;
                    var autocompleteStateElement = document.getElementById('autocomplete_state');
                    var autocompleteStateOptions = autocompleteStateElement.options;

                    for (var i = 0; i < autocompleteStateOptions.length; i++) {
                        if (autocompleteStateOptions[i].text.toLowerCase() === selectedState.toLowerCase()) {
                            autocompleteStateOptions[i].selected = true;
                            break;
                        }
                    }
                    break;
                }
            }
        }           
        
        document.querySelector("#autocomplete-suite").focus();
    }

    window.initAutocomplete = initAutocomplete;

    $(document).ready(function (){
        $('#updateProfileModal, #updatePasswordModal').on('hidden.bs.modal', function(){
            $(this).find('form')[0].reset();
        });

        $("#company_user_logo").on("change", function (){
            console.log ("123");
            $(this).parents("#company_user_logo_form").submit();
        });


        $('.view-password').mousedown(function(){
            $(this).closest('.input-group').find('input').attr('type','text');
        });

        $('.view-password').mouseup(function(){
            $(this).closest('.input-group').find('input').attr('type','password');
        });
        
        
        $("#update_user_profile_form").on("submit", function (){
            var instance = $(this).parsley();
            if (instance.isValid()){
                $(".update_user_profile_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
                $(".update_user_profile_btn").attr("disabled", true);
            } else {
                $(".update_user_profile_btn").html('Save Changes');
                $(".update_user_profile_btn").attr("disabled", false);
            }
        });
        
        $("#change_password_form").on("submit", function (){
            var instance = $(this).parsley();
            if (instance.isValid()){
                $(".change_password_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
                $(".change_password_btn").attr("disabled", true);
            } else {
                $(".change_password_btn").html('Save Changes');
                $(".change_password_btn").attr("disabled", false);
            }
        });
    });
</script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{ env('GOOGLE_MAP_API_KEY') }}&callback=initAutocomplete&libraries=places&v=weekly"></script>
<style>
    .pac-container
    {
        z-index: 1053 !important;
    }
</style>
@endsection
