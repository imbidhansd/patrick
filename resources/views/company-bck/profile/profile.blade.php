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
                                <h5 class="font-18 mb-1">
                                    {{ $company_detail->first_name.' '.$company_detail->last_name }}</h5>
                                <p class="text-muted mb-2">{{ '@'.$company_detail->username }}</p>
                            </div>

                            <button type="button" class="btn btn-success btn-sm width-sm waves-effect mt-2 waves-light"
                                data-toggle="modal" data-target="#updateProfileModal">Update Profile</button>

                            <button type="button" class="btn btn-danger btn-sm width-sm waves-effect mt-2 waves-light"
                                data-toggle="modal" data-target="#updatePasswordModal">Change Password</button>

                            <p class="sub-header mt-3">
                                {!! substr($company_detail->company_bio, 0, 200) !!}
                            </p>

                            <hr />

                            <div class="text-left">
                                <p class="text-muted font-13"><strong>Full Name :</strong>
                                    <span>{{ $company_detail->first_name.' '.$company_detail->last_name }}</span></p>
                                <p class="text-muted font-13"><strong>Mobile
                                        :</strong><span>{{ $company_detail->main_company_telephone }}</span></p>
                                <p class="text-muted font-13"><strong>Email :</strong>
                                    <span>{{ $company_detail->company_mailing_address }}</span></p>
                                <p class="text-muted font-13"><strong>Location :</strong>
                                    <span>{{ $company_detail->city }}</span></p>
                            </div>

                            <ul class="social-links list-inline mt-4">
                                <li class="list-inline-item">
                                    <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                        data-original-title="Facebook"><i class="fab fa-facebook-f"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                        data-original-title="Twitter"><i class="fab fa-twitter"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href=""
                                        data-original-title="Skype"><i class="fab fa-skype"></i></a>
                                </li>
                            </ul>

                        </div>

                    </div>
                    <!-- end card-box -->

                </div>
                <!-- end col -->

                <?php /* <div class="col-xl-9 col-md-8">
                    <h5 class="header-title">Expertise</h5>

                    <div class="row mt-3">
                        <div class="col-lg-3 col-sm-6 text-center">
                            <div class="pt-2" dir="ltr">
                                <input data-plugin="knob" data-width="120" data-height="120" data-linecap=round data-fgColor="#2abfcc" value="89" data-skin="tron" data-angleOffset="180" data-readOnly=true data-thickness=".1" />
                                <h6 class="text-muted mt-2">HTML5</h6>
                            </div>
                        </div>
                        <!-- end col-->

                        <div class="col-lg-3 col-sm-6 text-center">
                            <div class="pt-2" dir="ltr">
                                <input data-plugin="knob" data-width="120" data-height="120" data-linecap=round data-fgColor="#2abfcc" value="94" data-skin="tron" data-angleOffset="180" data-readOnly=true data-thickness=".1" />
                                <h6 class="text-muted mt-2">CSS3</h6>
                            </div>
                        </div>
                        <!-- end col-->

                        <div class="col-lg-3 col-sm-6 text-center">
                            <div class="pt-2" dir="ltr">
                                <input data-plugin="knob" data-width="120" data-height="120" data-linecap=round data-fgColor="#2abfcc" value="75" data-skin="tron" data-angleOffset="180" data-readOnly=true data-thickness=".1" />
                                <h6 class="text-muted mt-2">Wordpress</h6>
                            </div>
                        </div>
                        <!-- end col-->

                        <div class="col-lg-3 col-sm-6 text-center">
                            <div class="pt-2" dir="ltr">
                                <input data-plugin="knob" data-width="120" data-height="120" data-linecap=round data-fgColor="#2abfcc" value="85" data-skin="tron" data-angleOffset="180" data-readOnly=true data-thickness=".1" />
                                <h6 class="text-muted mt-2">AngularJs</h6>
                            </div>
                        </div>
                        <!-- end col-->

                    </div>
                    <!-- end row -->

                    <hr/>

                    <div class="row">
                        <div class="col-xl-8">
                            <h5 class="header-title">Experience</h5>

                            <div class=" pt-2">
                                <h5 class="font-16 mb-1">Lead designer / Developer</h5>
                                <p class="mb-0">websitename.com</p>
                                <p><b>2010-2015</b></p>

                                <p class="sub-header">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                                </p>
                            </div>

                            <hr/>

                            <div class="">
                                <h5 class="font-16 mb-1">Senior Graphic Designer</h5>
                                <p class="mb-0">coderthemes.com</p>
                                <p><b>2007-2009</b></p>

                                <p class="sub-header">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                                </p>
                            </div>
                        </div>
                        <!-- end col -->

                        <div class="col-xl-4">
                            <h5 class="header-title">Friends</h5>

                            <div class="inbox-widget">
                                <div>
                                    <div class="inbox-item">
                                        <div class="inbox-item-img"><img src="assets/images/users/avatar-2.jpg" class="rounded-circle" alt=""></div>
                                        <p class="inbox-item-author">Tomaslau</p>
                                        <p class="inbox-item-text">I've finished it! See you so...</p>
                                        <p class="inbox-item-date">
                                            <button type="button" class="btn btn-xs btn-success">Follow</button>
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <div class="inbox-item">
                                        <div class="inbox-item-img"><img src="assets/images/users/avatar-3.jpg" class="rounded-circle" alt=""></div>
                                        <p class="inbox-item-author">Stillnotdavid</p>
                                        <p class="inbox-item-text">This theme is awesome!</p>
                                        <p class="inbox-item-date">
                                            <button type="button" class="btn btn-xs btn-danger">Unfollow</button>
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <div class="inbox-item">
                                        <div class="inbox-item-img"><img src="assets/images/users/avatar-4.jpg" class="rounded-circle" alt=""></div>
                                        <p class="inbox-item-author">Kurafire</p>
                                        <p class="inbox-item-text">Nice to meet you</p>
                                        <p class="inbox-item-date">
                                            <button type="button" class="btn btn-xs btn-success">Follow</button>
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <div class="inbox-item">
                                        <div class="inbox-item-img"><img src="assets/images/users/avatar-5.jpg" class="rounded-circle" alt=""></div>
                                        <p class="inbox-item-author">Shahedk</p>
                                        <p class="inbox-item-text">Hey! there I'm available...</p>
                                        <p class="inbox-item-date">
                                            <button type="button" class="btn btn-xs btn-success">Follow</button>
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <div class="inbox-item">
                                        <div class="inbox-item-img"><img src="assets/images/users/avatar-6.jpg" class="rounded-circle" alt=""></div>
                                        <p class="inbox-item-author">Adhamdannaway</p>
                                        <p class="inbox-item-text">This theme is awesome!</p>
                                        <p class="inbox-item-date">
                                            <button type="button" class="btn btn-xs btn-success">Follow</button>
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->

                    <h5 class="header-title mt-2 mb-3">Recent Works</h5>

                    <div class="row">
                        <div class="col-md-4 col-sm-6">
                            <div class=" thumb">
                                <a href="#" class="image-popup" title="Screenshot-1">
                                    <img src="assets/images/shots/shot-1.png" class="thumb-img" alt="work-thumbnail">
                                </a>
                                <div class="gal-detail">
                                    <h5 class="font-18">Travel Guide</h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6">
                            <div class=" thumb">
                                <a href="#" title="Screenshot-2">
                                    <img src="assets/images/shots/shot-2.png" class="thumb-img" alt="work-thumbnail">
                                </a>
                                <div class="gal-detail">
                                    <h5 class="font-18">Interval timer (app concept)</h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6">
                            <div class=" thumb">
                                <a href="#" class="image-popup" title="Screenshot-3">
                                    <img src="assets/images/shots/shot-3.png" class="thumb-img" alt="work-thumbnail">
                                </a>
                                <div class="gal-detail">
                                    <h5 class="font-18">Ecommerce app</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col --> */ ?>

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

            {!! Form::open(['url' => url('update-profile'), 'class' => 'module_form ']) !!}
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('First Name') !!}
                    {!! Form::text('first_name', $company_detail->first_name, ['class' => 'form-control', 'required' =>
                    true, 'maxlength' => 255]) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('Last Name') !!}
                    {!! Form::text('last_name', $company_detail->last_name, ['class' => 'form-control', 'required' =>
                    true, 'maxlength' => 255]) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('Username') !!}
                    {!! Form::text('username', $company_detail->username, ['class' => 'form-control', 'required' =>
                    true, 'readonly' => true]) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('Email') !!}
                    {!! Form::text('email', $company_detail->email, ['class' => 'form-control', 'required' => true,
                    'readonly' => true]) !!}
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

            {!! Form::open(['url' => url('change-password'), 'class' => 'module_form ']) !!}
            <div class="modal-body">
                <div class="form-group">
                    <label>Old Password</label>
                    {!! Form::password('old_password', ['class' => 'form-control', 'required' => 'required', 'id' =>
                    'password', 'maxlength' => 255, 'data-parsley-uppercase' => 1, 'data-parsley-lowercase' => 1,
                    'data-parsley-number' => 1, 'data-parsley-special' => 1, 'data-parsley-minlength' => 6,
                    'data-parsley-maxlength' => 50]) !!}
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    {!! Form::password('new_password', ['class' => 'form-control', 'required' => 'required', 'id' =>
                    'new_password', 'maxlength' => 255, 'data-parsley-uppercase' => 1, 'data-parsley-lowercase' => 1,
                    'data-parsley-number' => 1, 'data-parsley-special' => 1, 'data-parsley-minlength' => 6,
                    'data-parsley-maxlength' => 50]) !!}
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    {!! Form::password('confirm_password', ['class' => 'form-control', 'required' => 'required',
                    'data-parsley-equalto' => '#new_password']) !!}
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
