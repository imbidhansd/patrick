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

    @include('admin.includes.formErrors')

    {!! Form::open(['id' => 'register_form', 'class' => 'module_form']) !!}

    <div class="row">
        <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-10 offset-sm-1">
            <ul class="progress_new">
                <li id="step_dot_1" class="progress_new_step progress_new__circle progress_new--active"></li>
                <li id="step_bar_1" class="progress_new_step progress_new__bar"></li>
                <li id="step_dot_2" class="progress_new_step progress_new__circle"></li>
                <li id="step_bar_2" class="progress_new_step progress_new__bar"></li>
                <li id="step_dot_3" class="progress_new_step progress_new__circle"></li>
                <li id="step_bar_3" class="progress_new_step progress_new__bar"></li>
                <li id="step_dot_4" class="progress_new_step progress_new__circle"></li>
                <li id="step_bar_4" class="progress_new_step progress_new__bar"></li>
                <li id="step_dot_5" class="progress_new_step progress_new__circle"></li>
                <li id="step_bar_5" class="progress_new_step progress_new__bar"></li>
                <li id="step_dot_6" class="progress_new_step progress_new__circle"></li>
                <li id="step_bar_6" class="progress_new_step progress_new__bar"></li>
                <li id="step_dot_7" class="progress_new_step progress_new__circle"></li>
            </ul>
        </div>
    </div>


    <div class="clearfix">&nbsp;</div>

    <div class="owl-carousel owl-theme step-carousel">

        <?php // Step 1 [Start] ?>
        <div class="item">
            @include('company.register.step1')
        </div>
        <?php // Step 1 [End] ?>

        <?php // Step 2 [Start] ?>
        <div class="item">
            @include('company.register.step2')
        </div>
        <?php // Step 2 [End] ?>

        <?php // Step 3.1 [Start] ?>
        <div class="item">
            @include ('company.register.step3.step1')
        </div>
        <?php // Step 3.1 [End] ?>


        <?php // Step 3.2 [Start] ?>
        <div class="item">
            @include ('company.register.step3.step2')
        </div>
        <?php // Step 3.2 [End] ?>

        <?php // Step 3.3 [Start] ?>
        <div class="item">
            @include ('company.register.step3.step3')
        </div>
        <?php // Step 3.3 [End] ?>

        <?php // Step 3.4 [Start] ?>
        <div class="item">
            @include ('company.register.step3.step4')
        </div>
        <?php // Step 3.4 [End] ?>

        <?php // Step 3.5 [Start] ?>
        <div class="item">
            @include ('company.register.step3.step5')
        </div>
        <?php // Step 3.5 [End] ?>
    </div>

    <div class="clearfix">&nbsp;</div>


    {!! Form::close() !!}

</div>

@endsection

@section ('page_js')

<!-- Owl -->
<link rel="stylesheet" href="{{ asset('thirdparty/owl/owl.carousel.min.css') }}">
<script src="{{ asset('thirdparty/owl/owl.carousel.min.js') }}"></script>

<script type="text/javascript">
    $(function() {

        // Set Active Step Number [Start]
        function setActiveStep(step_num){
            $('.progress_new_step').removeClass('progress_new--done').removeClass('progress_new--active');
            for (i=1; i<step_num; i++){
                $('#step_dot_' + i).addClass('progress_new--done progress_new--active');
                $('#step_bar_' + i).addClass('progress_new--done');
            }
            $('#step_dot_' + step_num).addClass('progress_new--active');
        }
        // Set Active Step Number [End]

        owl = $('.step-carousel').owlCarousel({
            loop: false,
            margin: 10,
            nav: false,
            items: 1,
            touchDrag: false,
            pullDrag: false,
            mouseDrag: false,
            autoHeight: true,
        });
        //$(".step-carousel").trigger("to.owl.carousel", [2, 1])

        owl.on('changed.owl.carousel', function(event) {
            setActiveStep(event.item.index + 1);

            var top = $('#register_form').offset().top - ($('#topnav').height() + 20)
                    $('html, body').animate({
                        scrollTop: top
                    }, 1000);

        })

        $(document).on('click', '.chk_all', function() {
            $(this).closest('.checkbox').find('input[type="checkbox"]').prop('checked', $(this).is(':checked'));
        });


        $('.back-btn').click(function() {
            if ($(this).data('step') == '3.5') {

                if ($('#secondary_main_category_id option').length == 0) {
                    $('.step-carousel').trigger('prev.owl.carousel');
                }

                if ($('#secondary_main_category_id option:checked').text() == 'None') {
                    $('.step-carousel').trigger('prev.owl.carousel');
                } else if ($('#secondary_main_category_id option').length <= 3) {
                    $('.step-carousel').trigger('prev.owl.carousel');
                }
            }
            $('.step-carousel').trigger('prev.owl.carousel');
        });

        $('#register_form').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                // Get Current Step and submit form

                /*setTimeout(function(){
                    $('.owl-item.active').find('.current_step_submit_btn').trigger('click');
                }, 10);*/

                //$('.owl-item.active').find('.current_step_submit_btn').trigger('click');
                e.preventDefault();
                return false;
            }
        });

        $('.last_input, .current_step_submit_btn').on('keydown', function(e) {
            if (e.keyCode == 9) {
                $(this).focus();
               e.preventDefault();
            }
        });


        /* Step 1 Action [Start] */

        $('#submit-step-1').click(function() {

            if ($('#register_form').parsley().validate({
                    group: 'step_1'
                })) {

                // Check for Email
                $('#email').parent().find('.parsley-errors-list').remove();

                $.ajax({
                    type: 'post',
                    url: '{{  url("check-available-email") }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'email': $('#email').val()
                    },
                    success: function(data) {
                        if (data.status == '0') {
                            $('#email').parent().find('.parsley-errors-list').remove();
                            $('#email').parent().append('<ul class="parsley-errors-list filled" id="parsley-id-9"><li class="parsley-required">' + data.message + '</li></ul>');

                        } else {

                            // Check for Username [Start]
                            $('#username').parent().find('.parsley-errors-list').remove();
                            $.ajax({
                                type: 'post',
                                url: '{{  url("check-available-username") }}',
                                data: {
                                    '_token': '{{ csrf_token() }}',
                                    'username': $('#username').val()
                                },
                                success: function(data) {
                                    if (data.status == '0') {
                                        $('#username').parent().append('<ul class="parsley-errors-list filled" id="parsley-id-9"><li class="parsley-required">' + data.message + '</li></ul>');
                                    } else {
                                        // Step 1 is correct
                                        $('.step-carousel').trigger('next.owl.carousel');
                                    }
                                },
                                error: function(e) {
                                    alert('alert');
                                },
                            });
                            // Check for Username [End]
                        }
                    },
                    error: function(e) {
                        alert('Error while checking valid email');
                    },
                });
                // Check for Email
            }

        });


        /* Step 1 Action [End] */



        /* Step 2 Action [Start] */


        $('#submit-step-2').click(function() {
            if ($('#register_form').parsley().validate({
                    group: 'step_2'
                })) {
                    // Step 2 is correct
                    $('.step-carousel').trigger('next.owl.carousel');
            }
        });


        /* Step 2 Action [End] */



        /* Step 3 Actions [Start] */



        /* Step 3.1 [Start] */


        /* Get Top Level Categories */
        $('#trade_id').change(function() {
            $('.top_level_categories_container').html('');
            if ($('#trade_id').val() > 0) {
                $.ajax({
                    type: 'post',
                    url: '{{ url("get-top-level-category-list") }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'trade_id': $('#trade_id').val()
                    },
                    success: function(data) {
                        $('.top_level_categories_container').html(data);
                        $('.step-carousel').trigger('refresh.owl.carousel');
                    },
                    error: function(data) {
                        alert('error');
                    },
                });
            } else {

            }
        });


        $(document).on('click', '.next_btn_3_1', function() {
            // check for checkbox selection
            $('#main_category_id').html('');
            $(".main_service_category_container").html('');

            $('#secondary_main_category_id').html('');
            $(".secondary_service_category_container").html('');

            if ($('.chk_top_level_category_id:checked').length > 0) {

                var data = {
                    '_token': '{{ csrf_token() }}',
                    'top_level_category_ids[]': []
                };
                $(".chk_top_level_category_id:checked").each(function() {
                    data['top_level_category_ids[]'].push($(this).val());
                });


                // Ajax for getting Main Categories for Step 2
                $.ajax({
                    type: 'post',
                    url: '{{ url("get-main-category-list") }}',
                    data: data,
                    success: function(data) {
                        $('#main_category_id').html(data);
                        $('#main_category_id').trigger('change');
                        $('.step-carousel').trigger('next.owl.carousel');
                    },
                    error: function(e) {
                        alert('error');
                    },
                });

            } else {
                Swal.fire(
                    'Warning',
                    'Please select at least one category',
                    'warning'
                );
            }
        });
        /* Step 3.1 [End] */





        /* Step 3.2 [Start] */

        $(document).on('change', '#main_category_id', function() {
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
                success: function(data) {
                    $(".main_service_category_container").html(data);
                    $('.owl-carousel').trigger('refresh.owl.carousel');
                },
                error: function(data) {
                    alert('error');
                },
            });
        });



        $(document).on('click', '.next_btn_3_2', function() {

            if ($('#main_category_id').val() == '') {
                Swal.fire(
                    'Warning',
                    'Please select Main Category',
                    'warning'
                );

                return false;
            }

            // check for checkbox selection
            if ($('.chk_service_category_id_step_2:checked').length > 0) {

                // Ajax for getting Main Categories for Step 3
                var data = {
                    '_token': '{{ csrf_token() }}',
                    'main_category_id': $('#main_category_id').val(),
                    'top_level_category_ids[]': []
                };
                $(".chk_top_level_category_id:checked").each(function() {
                    data['top_level_category_ids[]'].push($(this).val());
                });

                $.ajax({
                    type: 'post',
                    url: '{{ url("get-main-category-list") }}',
                    data: data,
                    success: function(data) {

                        if (data == 'false') {
                            $('.step-carousel').trigger('next.owl.carousel');
                            $('.step-carousel').trigger('next.owl.carousel');
                        } else {
                            $('#secondary_main_category_id').html(data).trigger('change');
                            $('.secondary_service_category_container').html('');
                        }

                        $('.step-carousel').trigger('next.owl.carousel');

                    },
                    error: function(e) {
                        alert('error');
                    },
                });


            } else {
                Swal.fire(
                    'Warning',
                    'Please select at least one category',
                    'warning'
                );
            }
        });



        /* Step 3.2 [End] */


        /* Step 3.3 [Start] */

        $(document).on('change', '#secondary_main_category_id', function() {
            $(".secondary_service_category_container").html('');
            $.ajax({
                type: 'post',
                url: '{{ url("get-service-category-list") }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'main_category_id': $('#secondary_main_category_id').val()
                },
                success: function(data) {
                    $(".secondary_service_category_container").html(data);
                    $('.owl-carousel').trigger('refresh.owl.carousel');
                },
                error: function(data) {
                    alert('error');
                },
            });
        });



        $(document).on('click', '.next_btn_3_3', function() {

            //alert ($('#main_category_id option').length - 1);
            //return false;

            // If No Secondary Category Selected and Click on Next
            if ($('#secondary_main_category_id option:checked').text() == 'None') {
                $('.step-carousel').trigger('next.owl.carousel');
                $('.step-carousel').trigger('next.owl.carousel');
                return false;
            }


            if ($('#secondary_main_category_id').val() == '') {
                Swal.fire(
                    'Warning',
                    'Please select at least one category',
                    'warning'
                );
                return false;
            }

            // If secondary main category selected and check for checkbox selection
            if ($('#secondary_main_category_id').val() != '' && $('.chk_service_category_id_step_2:checked').length > 0) {

                // Check for next Step 4 Or 5

                if ($('#secondary_main_category_id option').length > 3) {
                    // Call ajax for Step 3.4
                    // Ajax for getting Main Categories for Step 3
                    var data = {
                        '_token': '{{ csrf_token() }}',
                        'main_category_id': $('#main_category_id').val(),
                        'secondary_main_category_id': $('#secondary_main_category_id').val(),
                        'top_level_category_ids[]': []
                    };

                    $(".chk_top_level_category_id:checked").each(function() {
                        data['top_level_category_ids[]'].push($(this).val());
                    });

                    $.ajax({
                        type: 'post',
                        url: '{{ url("get-rest-category-list") }}',
                        data: data,
                        success: function(data) {
                            $('.rest_service_category_container').html(data);
                            $('.owl-carousel').trigger('refresh.owl.carousel');
                            $('.step-carousel').trigger('next.owl.carousel');

                            var top = $('#card_step_3').offset().top - ($('#topnav').height() + 20)
                            $('html, body').animate({
                                scrollTop: top
                            }, 1000);
                        },
                        error: function(e) {
                            alert('error');
                        },
                    });

                } else {
                    $('.step-carousel').trigger('next.owl.carousel');
                    $('.step-carousel').trigger('next.owl.carousel');

                    var top = $('#card_step_3').offset().top - ($('#topnav').height() + 20)
                    $('html, body').animate({
                        scrollTop: top
                    }, 1000);
                }

            } else {
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


        $('.include_rest_categories').click(function() {
            if ($(this).val() == 'no') {
                $('.rest_service_category_container').slideUp();
            } else if ($(this).val() == 'yes') {
                $('.rest_service_category_container').slideDown();
            }
        });


        $('.next_btn_3_4').click(function() {

            if ($('.include_rest_categories:checked').val() == 'no') {
                $('.step-carousel').trigger('next.owl.carousel');
            } else if ($('.include_rest_categories:checked').val() == 'yes' && $('.chk_service_category_id_step_4:checked').length > 0) {
                $('.step-carousel').trigger('next.owl.carousel');
            } else {
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

        $('.show_map').click(function() {
            var zipcode = parseInt($('#zipcode').val());

            if (!(zipcode >= 10000 && zipcode <= 99999)) {
                Swal.fire(
                    'Warning',
                    'Please enter valid US postcode',
                    'warning'
                );
            } else {
                getGoogleMaps(1);
            }
        });

        $('#mile_range').change(function() {
            if ($(this).val() > 0) {
                getGoogleMaps($(this).val());
            } else {
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
