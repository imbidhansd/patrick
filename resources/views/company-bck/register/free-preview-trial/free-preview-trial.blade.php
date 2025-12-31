<?php
    $admin_page_title = 'Free Preview Trial';
?>
@extends('company.layout')


@section ('content')
@include('admin.includes.breadcrumb')
@include('flash::message')

<!-- Basic Form Wizard -->

<div class="card-box">
    <h1 class="text-center">Free Preview Trial</h1>
    <div class="clearfix">&nbsp;</div>
    <div class="clearfix">&nbsp;</div>


    @include('admin.includes.formErrors')


    {!! Form::open(['id' => 'register_form', 'class' => 'module_form ']) !!}



    <div class="card" id="card_step_1">
        <div class="card-header bg-primary show">
            <h5 data-toggle="collapse" href="#card_body_step_1" class="text-white mt-0 mb-0">
                1. Account Information
                <i class="fas float-md-right"></i>
            </h5>
        </div>

        <div id="card_body_step_1" class="card-body collapse show">
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('First Name') !!}
                        {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => '',
                        'required'
                        =>
                        true, 'maxlength' => 255, 'data-parsley-group' => 'step_1']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('Last Name') !!}
                        {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => '',
                        'required' =>
                        true, 'maxlength' => 255, 'data-parsley-group' => 'step_1']) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('Email Address') !!}
                        {!! Form::text('email', null, ['id' => 'email', 'class' => 'form-control', 'placeholder' =>
                        '',
                        'required' =>
                        true, 'maxlength' => 255, 'data-parsley-group' => 'step_1']) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('Enter Username') !!}
                        {!! Form::text('username', null, ['id' => 'username','class' => 'form-control', 'placeholder' =>
                        '', 'required' => true, 'maxlength' => 255, 'data-parsley-group' => 'step_1',
                        'data-parsley-type' => 'alphanum']) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('Enter Password') !!}
                        {!! Form::password('password', ['id' => 'password','class' => 'form-control', 'placeholder'
                        =>
                        '',
                        'required' =>
                        true, 'maxlength' => 255, 'data-parsley-group' => 'step_1', 'data-parsley-uppercase' => 1,
                        'data-parsley-lowercase' => 1, 'data-parsley-number' => 1, 'data-parsley-special' => 1,
                        'data-parsley-minlength' => 6, 'data-parsley-maxlength' => 50]) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('Enter Confirm Password') !!}
                        {!! Form::password('confirm_password', ['class' => 'form-control', 'placeholder' => '',
                        'required' =>
                        true, 'maxlength' => 255, 'data-parsley-group' => 'step_1', 'data-parsley-equalto' =>
                        '#password']) !!}
                    </div>
                </div>

            </div>

            <div class="clearfix">&nbsp;</div>
            <button type="button" class="btn btn-info float-md-right next-btn" id="submit-step-1">Save &
                Proceed</button>

            <div class="clearfix">&nbsp;</div>

        </div>
    </div>


    <div class="card" id="card_step_2">
        <div class="card-header bg-primary">
            <h5 href="#card_body_step_2" class="text-white mt-0 mb-0">
                2. Company Information
                <i class="fas float-md-right"></i>
            </h5>
        </div>
        <div id="card_body_step_2" class="card-body collapse">

            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('Company Name') !!}
                        {!! Form::text('company_name', null, ['class' => 'form-control', 'placeholder' => '',
                        'required'
                        =>
                        true, 'maxlength' => 255, 'data-parsley-group' => 'step_2']) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('Company Website') !!}
                        {!! Form::text('company_website', null, ['class' => 'form-control', 'placeholder' => '',
                        'required' =>
                        true, 'maxlength' => 255, 'data-parsley-group' => 'step_2']) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('Main Company Telephone') !!}
                        {!! Form::text('main_company_telephone', null, ['class' => 'form-control',
                        'required' => true, 'maxlength' => 255, 'data-parsley-group' => 'step_2', 'data-toggle' =>
                        'input-mask',
                        'data-mask-format' => '(000) 000-0000']) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('Secondary Telephone') !!}
                        {!! Form::text('secondary_telephone', null, ['class' => 'form-control',
                        'required' => false, 'maxlength' => 255, 'data-parsley-group' => 'step_2',
                        'data-toggle' => 'input-mask',
                        'data-mask-format' => '(000) 000-0000']) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('Company Mailling Address') !!}
                        {!! Form::text('company_mailing_address', null, ['class' => 'form-control',
                        'required' => true, 'maxlength' => 255, 'data-parsley-group' => 'step_2']) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('Suite') !!}
                        {!! Form::text('suite', null, ['class' => 'form-control',
                        'required' => false, 'maxlength' => 255, 'data-parsley-group' => 'step_2']) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('City') !!}
                        {!! Form::text('city', null, ['class' => 'form-control',
                        'required' => true, 'maxlength' => 255, 'data-parsley-group' => 'step_2']) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('State') !!}
                        {!! Form::select('state_id', $states, null, ['class' => 'form-control',
                        'required' => true, 'maxlength' => 255, 'data-parsley-group' => 'step_2']) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('Zipcode') !!}
                        {!! Form::text('zipcode', null, ['class' => 'form-control',
                        'required' => true, 'maxlength' => 5, 'data-parsley-group' => 'step_2',
                        'data-toggle' => 'input-mask',
                        'data-mask-format' => '00000']) !!}
                    </div>
                </div>

            </div>

            <div class="clearfix">&nbsp;</div>
            <button type="button" class="btn btn-info float-md-right next-btn" id="submit-step-2">Save &
                Proceed</button>
            <div class="clearfix">&nbsp;</div>
        </div>
    </div>


    <div class="card" id="card_step_3">
        <div class="card-header bg-primary">
            <h5 href="#card_body_step_3" class="text-white mt-0 mb-0">
                3. Lead Generation Settings
                <i class="fas float-md-right"></i>
            </h5>
        </div>

        <div id="card_body_step_3" class="card-body collapse">

            <div class="progress">
                <div class="progress-bar bg-pink" role="progressbar" aria-valuenow="20" aria-valuemin="0"
                    aria-valuemax="100" style="width: 20%;">
                    20%
                </div>
            </div>

            <div class="owl-carousel owl-theme step3-steps">
                <?php // Step 3.1 [Start] ?>
                <div class="item">
                    @include ('company.register.free-preview-trial.step3.step1')
                </div>
                <?php // Step 3.1 [End] ?>




                <?php // Step 3.2 [Start] ?>
                <div class="item">
                    @include ('company.register.free-preview-trial.step3.step2')
                </div>
                <?php // Step 3.2 [End] ?>




                <?php // Step 3.3 [Start] ?>
                <div class="item">
                    @include ('company.register.free-preview-trial.step3.step3')
                </div>
                <?php // Step 3.3 [End] ?>



                <?php // Step 3.4 [Start] ?>
                <div class="item">
                    @include ('company.register.free-preview-trial.step3.step4')
                </div>
                <?php // Step 3.4 [End] ?>



                <?php // Step 3.5 [Start] ?>
                <div class="item">
                    @include ('company.register.free-preview-trial.step3.step5')
                </div>
                <?php // Step 3.5 [End] ?>

            </div>



            <div class="clearfix">&nbsp;</div>

        </div>
    </div>



    {!! Form::close() !!}



</div>


@endsection

@section ('page_js')


<!-- Owl -->
<link rel="stylesheet" href="{{ asset('thirdparty/owl/owl.carousel.min.css') }}">
<script src="{{ asset('thirdparty/owl/owl.carousel.min.js') }}"></script>

<script type="text/javascript">
    $(function(){

        owl = $('.step3-steps').owlCarousel({
            loop:false,
            margin:10,
            nav:false,
            items: 1,
            touchDrag: false,
            pullDrag: false,
            mouseDrag: false,
            autoHeight:true,
        });

        owl.on('changed.owl.carousel', function(event) {

            var progress = (event.item.index + 1) * 20;
            $('.progress-bar').attr('aria-valuenow', progress).css('width', progress + '%').html(progress + '%');
            //alert (progress);
            //<div class="progress-bar bg-pink" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%;">
        })


        $(document).on('click', '.chk_all', function(){
            $(this).closest('.checkbox').find('input[type="checkbox"]').prop('checked', $(this).is(':checked'));
        });


        $(document).on('click', 'h5[data-toggle="collapse"]', function(){
            var cur_href_id = $(this).attr('href');

            $('.card-body').each(function(){
                if ("#" + $(this).attr('id') != cur_href_id){
                    $(this).removeClass('show');
                }
            });
        });


        $('.back-btn').click(function(){
            if ($(this).data('step') == '3.5'){

                if($('#secondary_main_category_id option').length == 0){
                    owl.trigger('prev.owl.carousel');
                }

                if ($('#secondary_main_category_id option:checked').text() == 'None'){
                    owl.trigger('prev.owl.carousel');
                }else if ($('#secondary_main_category_id option').length <= 3){
                    owl.trigger('prev.owl.carousel');
                }


            }
            owl.trigger('prev.owl.carousel');
        });


        $('#register_form').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
              e.preventDefault();
              return false;
            }
          });


          /* Step 1 Action [Start] */

          $('#submit-step-1').click(function(){

            if ($('#register_form').parsley().validate({group: 'step_1'})) {


                // Check for Email
                $('#email').parent().find('.parsley-errors-list').remove();

                $.ajax({
                    type: 'post',
                    url: '{{  url("check-available-email") }}',
                    data: {'_token' : '{{ csrf_token() }}', 'email': $('#email').val() },
                    success: function(data){
                        if (data.status == '0'){
                            $('#email').parent().find('.parsley-errors-list').remove();
                            $('#email').parent().append('<ul class="parsley-errors-list filled" id="parsley-id-9"><li class="parsley-required">' + data.message + '</li></ul>');

                        }else{

                            // Check for Username [Start]
                            $('#username').parent().find('.parsley-errors-list').remove();
                            $.ajax({
                                type: 'post',
                                url: '{{  url("check-available-username") }}',
                                data: {'_token' : '{{ csrf_token() }}', 'username': $('#username').val() },
                                success: function(data){
                                    if (data.status == '0'){
                                        $('#username').parent().append('<ul class="parsley-errors-list filled" id="parsley-id-9"><li class="parsley-required">' + data.message + '</li></ul>');
                                    }else{

                                        // Step 1 is correct

                                        $('#card_step_1').find('h5').trigger('click').find('.fas').addClass('fa-check');

                                        setTimeout(function(){
                                            if (!$('#card_body_step_2').hasClass('show')){
                                                $('#card_step_2').find('h5').data('toggle', 'collapse').attr('data-toggle', 'collapse').trigger('click');
                                            }
                                            var top = $('#card_step_2').offset().top - ($('#topnav').height() + 20)
                                            $('html, body').animate({
                                                scrollTop: top
                                            }, 1000);
                                        }, 500);

                                    }
                                },
                                error: function(e){
                                    alert ('alert');
                                },
                            });
                            // Check for Username [End]
                        }
                    },
                    error: function(e){
                        alert ('Error while checking valid email');
                    },
                });
                // Check for Email

            }

          });


          /* Step 1 Action [End] */



          /* Step 2 Action [Start] */


          $('#submit-step-2').click(function(){
            if ($('#register_form').parsley().validate({group: 'step_2'})) {
                // Step 2 is correct

                $('#card_step_2').find('h5').trigger('click').find('.fas').addClass('fa-check');
                $('#card_body_step_1').removeClass('show');

                setTimeout(function(){
                    if (!$('#card_body_step_3').hasClass('show')){
                        $('#card_step_3').find('h5').data('toggle', 'collapse').attr('data-toggle', 'collapse').trigger('click');
                    }
                    var top = $('#card_step_3').offset().top - ($('#topnav').height() + 20)
                    $('html, body').animate({
                        scrollTop: top
                    }, 1000);
                }, 500);
            }
          });
          /* Step 2 Action [End] */


        $('.next-btn').click(function(){
            var cur_step = $(this).data('cur_step');

            if ($('#register_form').parsley().validate({group: 'step_' + cur_step})) {

            if (cur_step == '1'){
                // Check for Email
                $('#email').parent().find('.parsley-errors-list').remove();

                $.ajax({
                    type: 'post',
                    url: '{{  url("check-available-email") }}',
                    data: {'_token' : '{{ csrf_token() }}', 'email': $('#email').val() },
                    success: function(data){
                        if (data.status == '0'){
                            $('#email').parent().find('.parsley-errors-list').remove();
                            $('#email').parent().append('<ul class="parsley-errors-list filled" id="parsley-id-9"><li class="parsley-required">' + data.message + '</li></ul>');

                        }else{

                            // Check for Username [Start]
                            $('#username').parent().find('.parsley-errors-list').remove();
                            $.ajax({
                                type: 'post',
                                url: '{{  url("check-available-username") }}',
                                data: {'_token' : '{{ csrf_token() }}', 'username': $('#username').val() },
                                success: function(data){
                                    if (data.status == '0'){
                                        $('#username').parent().append('<ul class="parsley-errors-list filled" id="parsley-id-9"><li class="parsley-required">' + data.message + '</li></ul>');
                                    }else{
                                        owl.trigger('next.owl.carousel');
                                    }
                                },
                                error: function(e){
                                    alert ('alert');
                                },
                            });
                            // Check for Username [End]
                        }
                    },
                    error: function(e){
                        alert ('Error while checking valid email');
                    },
                });
                // Check for Email
            }else if (cur_step == '2'){
                owl.trigger('next.owl.carousel');
            }

            }


            $('.owl-carousel').trigger('refresh.owl.carousel');

        });


        /* Step 3 Actions [Start] */



        /* Step 3.1 [Start] */


        /* Get Top Level Categories */
        $('#trade_id').change(function(){
            $('.top_level_categories_container').html('');
            if ($('#trade_id').val() > 0){
                $.ajax({
                    type: 'post',
                    url: '{{ url("get-top-level-category-list") }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'trade_id': $('#trade_id').val()
                    },
                    success: function(data){
                        $('.top_level_categories_container').html(data);
                        $('.owl-carousel').trigger('refresh.owl.carousel');
                     },
                    error: function(data){ alert ('error'); },
                });
            }else{

            }

        });


        $(document).on('click', '.next_btn_3_1', function(){
            // check for checkbox selection
            $('#main_category_id').html('');
            $(".main_service_category_container").html('');

            $('#secondary_main_category_id').html('');
            $(".secondary_service_category_container").html('');

            if ($('.chk_top_level_category_id:checked').length > 0){

                var data = { '_token': '{{ csrf_token() }}' ,'top_level_category_ids[]' : []};
                $(".chk_top_level_category_id:checked").each(function() {
                    data['top_level_category_ids[]'].push($(this).val());
                });


                // Ajax for getting Main Categories for Step 2
                $.ajax({
                    type: 'post',
                    url: '{{ url("get-main-category-list") }}',
                    data: data,
                    success: function(data){
                        $('#main_category_id').html(data);
                        $('.step3-steps').trigger('next.owl.carousel');

                        var top = $('#card_step_3').offset().top - ($('#topnav').height() + 20)
                        $('html, body').animate({
                            scrollTop: top
                        }, 1000);


                    },
                    error: function(e){
                        alert ('error');
                    },
                });

            }else{
                Swal.fire(
                    'Warning',
                    'Please select at least one category',
                    'warning'
                );
            }
        });


        /* Step 3.1 [End] */





        /* Step 3.2 [Start] */

        $(document).on('change', '#main_category_id', function(){
            $(".main_service_category_container").html('');
            // For step 3.3 //
            $(".secondary_service_category_container").html('');
            $('#secondary_main_category_id').html('');
            // For step 3.3 //
            $.ajax({
                type: 'post',
                url: '{{ url("get-service-category-list") }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'main_category_id': $('#main_category_id').val()
                },
                success: function(data){
                    $(".main_service_category_container").html(data);
                    $('.owl-carousel').trigger('refresh.owl.carousel');
                 },
                error: function(data){ alert ('error'); },
            });
        });



        $(document).on('click', '.next_btn_3_2', function(){

            if ($('#main_category_id').val() == ''){
                Swal.fire(
                    'Warning',
                    'Please select Main Category',
                    'warning'
                );

                return false;
            }

            // check for checkbox selection
            if ($('.chk_service_category_id_step_2:checked').length > 0){

                // Ajax for getting Main Categories for Step 3
                var data = { '_token': '{{ csrf_token() }}' , 'main_category_id': $('#main_category_id').val() ,'top_level_category_ids[]' : []};
                $(".chk_top_level_category_id:checked").each(function() {
                    data['top_level_category_ids[]'].push($(this).val());
                });

                $.ajax({
                    type: 'post',
                    url: '{{ url("get-main-category-list") }}',
                    data: data,
                    success: function(data){

                        if (data == 'false'){
                            $('.step3-steps').trigger('next.owl.carousel');
                            $('.step3-steps').trigger('next.owl.carousel');
                        }else{
                            $('#secondary_main_category_id').html(data);
                            $('.secondary_service_category_container').html('');

                        }

                        $('.step3-steps').trigger('next.owl.carousel');

                        var top = $('#card_step_3').offset().top - ($('#topnav').height() + 20)
                        $('html, body').animate({
                            scrollTop: top
                        }, 1000);


                    },
                    error: function(e){
                        alert ('error');
                    },
                });


            }else{
                Swal.fire(
                    'Warning',
                    'Please select at least one category',
                    'warning'
                );
            }
        });



        /* Step 3.2 [End] */


        /* Step 3.3 [Start] */

        $(document).on('change', '#secondary_main_category_id', function(){
            $(".secondary_service_category_container").html('');
            $.ajax({
                type: 'post',
                url: '{{ url("get-service-category-list") }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'main_category_id': $('#secondary_main_category_id').val()
                },
                success: function(data){
                    $(".secondary_service_category_container").html(data);
                    $('.owl-carousel').trigger('refresh.owl.carousel');
                 },
                error: function(data){ alert ('error'); },
            });
        });



        $(document).on('click', '.next_btn_3_3', function(){

            //alert ($('#main_category_id option').length - 1);
            //return false;

            // If No Secondary Category Selected and Click on Next
            if ($('#secondary_main_category_id option:checked').text() == 'None'){
                $('.step3-steps').trigger('next.owl.carousel');
                $('.step3-steps').trigger('next.owl.carousel');
                return false;
            }


            if ($('#secondary_main_category_id').val() == ''){
                Swal.fire(
                    'Warning',
                    'Please select at least one category',
                    'warning'
                );
                return false;
            }

            // If secondary main category selected and check for checkbox selection
            if ($('#secondary_main_category_id').val() != '' && $('.chk_service_category_id_step_2:checked').length > 0){

                // Check for next Step 4 Or 5

                if ($('#secondary_main_category_id option').length > 3){
                    // Call ajax for Step 3.4
                    // Ajax for getting Main Categories for Step 3
                    var data = {
                        '_token': '{{ csrf_token() }}' ,
                        'main_category_id': $('#main_category_id').val() ,
                        'secondary_main_category_id': $('#secondary_main_category_id').val() ,
                        'top_level_category_ids[]' : []
                    };

                    $(".chk_top_level_category_id:checked").each(function() {
                        data['top_level_category_ids[]'].push($(this).val());
                    });

                    $.ajax({
                        type: 'post',
                        url: '{{ url("get-rest-category-list") }}',
                        data: data,
                        success: function(data){
                            $('.rest_service_category_container').html(data);
                            $('.owl-carousel').trigger('refresh.owl.carousel');
                            $('.step3-steps').trigger('next.owl.carousel');

                            var top = $('#card_step_3').offset().top - ($('#topnav').height() + 20)
                            $('html, body').animate({
                                scrollTop: top
                            }, 1000);
                        },
                        error: function(e){
                            alert ('error');
                        },
                    });

                }else{
                    $('.step3-steps').trigger('next.owl.carousel');
                    $('.step3-steps').trigger('next.owl.carousel');

                    var top = $('#card_step_3').offset().top - ($('#topnav').height() + 20)
                    $('html, body').animate({
                        scrollTop: top
                    }, 1000);
                }

            }else{
                Swal.fire(
                    'Warning',
                    'Please select at least one category',
                    'warning'
                );
                return false;
            }
        });


        /* Step 3.3 [End] */




        /* Step 3.4 [Start] */


        $('.include_rest_categories').click(function(){
            if ($(this).val() == 'no'){
                $('.rest_service_category_container').slideUp();
            }else if ($(this).val() == 'yes'){
                $('.rest_service_category_container').slideDown();
            }
        });


        $('.next_btn_3_4').click(function(){

            if ($('.include_rest_categories:checked').val() == 'no'){
                $('.step3-steps').trigger('next.owl.carousel');
            }else if ($('.include_rest_categories:checked').val() == 'yes' && $('.chk_service_category_id_step_4:checked').length > 0){
                $('.step3-steps').trigger('next.owl.carousel');
            }else{
                Swal.fire(
                    'Warning',
                    'Please select at least one category',
                    'warning'
                );
                return false;
            }

            var top = $('#card_step_3').offset().top - ($('#topnav').height() + 20)
            $('html, body').animate({
                scrollTop: top
            }, 1000);

        });

        /* Step 3.4 [End] */




        /* Step 3.5 [Start] */

        $('.show_map').click(function(){
            var zipcode = parseInt($('#zipcode').val());

            if (!(zipcode >= 10000 && zipcode <= 99999)){
                Swal.fire(
                    'Warning',
                    'Please enter valid US postcode',
                    'warning'
                );
            }else{
                getGoogleMaps(1);
            }
        });

        $('#mile_range').change(function(){
            if ($(this).val() > 0){
                getGoogleMaps($(this).val());
            }else{
                getGoogleMaps(1);
            }
        });


        /* Step 3.5 [End] */


        /* Step 3 Actions [End] */



    });
</script>

<!-- Plugins js -->
<script src="{{ asset('themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>
<script src="{{ asset('themes/admin/assets/js/pages/form-masks.init.js') }}"></script>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{ env('GOOGLE_MAP_API_KEY') }}"></script>
<script src="{{ asset('js/zipcode-radius.js') }}"></script>

@endsection
