@extends('find_a_pro.layout')

@section ('content')

    @php
        $categoryMapping = [
            "Grading/Gravel/Crushed Stone" => "Grading",
        ];
    @endphp

    <!-- Hero -->
    <section class="hero_sec">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                    <div class="details">
                        <h1>Find Home Improvement <strong>Contractors</strong> You Can <strong>Trust</strong></h1>
                        <h3 class="d-none d-sm-none d-md-block">Search Now</h3>

                        @php
                            $selected_main_catgory = '';
                            $selected_main_catgory_id = '';
                        @endphp

                        @if(isset($main_category_item) && !is_null($main_category_item))
                                            @php
                                                $selected_main_catgory = $main_category_item->title;
                                                $selected_main_catgory_id = $main_category_item->id;
                                            @endphp
                        @endif

                        <div class="search d-none d-sm-none d-md-block">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="main_category_search"
                                    placeholder="Type here to search" value="{{ $selected_main_catgory }}"
                                    aria-label="Recipient's username" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if (isset($top_search_top_level_category_list) && count($top_search_top_level_category_list) > 0)
        <!-- Category Sec -->
        <section class="cat_section">
            <div class="container">
                <h2 class="global_title">Top Searches</h2>
                <div class="row">
                    @foreach ($top_search_top_level_category_list as $top_search_top_level_category_item)
                        <div class="col-xl-2 col-lg-2 col-md-4 col-xs-6 col-6">
                            <a href="javascript:;" class="top_level_search" data-id="{{ $top_search_top_level_category_item->id }}">
                                <div class="cat_block_v1">
                                    <i>
                                        <img src="{{ $top_search_top_level_category_item->top_search_image }}" width="100%" alt="">
                                    </i>
                                    <h4>{{ $categoryMapping[$top_search_top_level_category_item->title] ?? $top_search_top_level_category_item->title }}
                                    </h4>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Need Sec -->
    <section class="need_sec">
        <div class="container">
            @if (isset($contractor_top_level_category_list) && count($contractor_top_level_category_list) > 0)
                    <h2 class="global_title">Find A Home Improvement Pro For Your Next Project</h2>
                    <div class="row need_sec_1">
                        @php
                            $contractor_top_level_category_arr = array_chunk($contractor_top_level_category_list->toArray(), ceil(count($contractor_top_level_category_list) / 3));
                        @endphp

                        @foreach ($contractor_top_level_category_arr as $arr_item)
                            <div class="col-xl-4 col-lg-4 col-md-5 col-xs-12">
                                <?php        /* <ul class="list_data"> */ ?>
                                @foreach ($arr_item as $item)
                                    <div class="check_detail">
                                        <label id="a">
                                            <input type="radio" name="top_level_top_search" class="top_level_top_search"
                                                value="{{ $item['id'] }}" />
                                            <span class="lbl">{{ $item['title'] }}</span>
                                        </label>
                                    </div>

                                    <?php            /* <li><a href="javascript:;" class="top_level_top_search" data-id="{{ $item['id'] }}">{{ $item['title'] }}</a></li> */ ?>
                                @endforeach
                                <?php        /* </ul> */ ?>
                            </div>
                        @endforeach
                    </div>
            @endif


            @if (isset($professional_top_level_category_list) && count($professional_top_level_category_list) > 0)
                    <h2 class="global_title">Find A Pro For Your Professional Needs</h2>
                    <div class="row">
                        @php
                            $professional_top_level_category_arr = array_chunk($professional_top_level_category_list->toArray(), ceil(count($professional_top_level_category_list) / 3));
                        @endphp

                        @foreach ($professional_top_level_category_arr as $arr_item)
                            <div class="col-xl-4 col-lg-4 col-md-5 col-xs-12">
                                <?php        /* <ul class="list_data"> */ ?>
                                @foreach ($arr_item as $item)
                                    <div class="check_detail">
                                        <label id="a">
                                            <input type="radio" name="top_level_top_search" class="top_level_top_search"
                                                value="{{ $item['id'] }}" />
                                            <span class="lbl">{{ $item['title'] }}</span>
                                        </label>
                                    </div>

                                    <?php            /* <li><a href="javascript:;" class="top_level_top_search" data-id="{{ $item['id'] }}">{{ $item['title'] }}</a></li> */ ?>
                                @endforeach
                                <?php        /* </ul> */ ?>
                            </div>
                        @endforeach
                    </div>
            @endif
        </div>
    </section>

    <!-- Service Category Sec -->
    <section class="service_sec" id="main_categories" style="display:none;">
    </section>

    <!-- Service Category Type Sec -->
    <section class="service_sec cat_section text-center" id="service_category_types" style="display:none;">
    </section>

    <!-- Service Category Sec -->
    <section class="service_sec" id="service_categories" style="display:none;">
    </section>

    <!-- Service Category Sec -->
    <section class="service_sec" id="timeframe_selection_div" style="display:none;">
        <div class="container">
            <!-- Service -->
            <div class="service_inn">
                <h2 class="global_title text-center">What is your timeframe for completion?</h2>
                <div class="list_grp">
                    @php
                        $color = ['green', 'yellow', 'red'];
                    @endphp

                    @foreach ($timeframe as $key => $item)
                        <div class="check_detail">
                            <label id="a" class="{{ $color[$key] }}">
                                <input type="radio" name="select_timeframe" class="select_timeframe" value="{{ $item }}" />
                                <span class="lbl">{{ $item }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="btn_block">
                    <button type="button" class="btn previous_section">Previous</button>
                    <?php /* <button type="button" class="btn next_section">Next</button> */ ?>
                </div>
                <div class="clearfix"></div>
                <a href="javascript:;" class="start_over">Start Over</a>
            </div>
        </div>
    </section>

    <!-- Service Sec -->
    <section class="service_sec" id="find_a_pro_form_div" style="display:none;">
        <div class="container">
            <!-- Form -->
            <div class="form_sec">
                {!! Form::open(['url' => url('find-a-pro/generate-lead'), 'class' => 'module_form', 'id' => 'find_a_pro_form']) !!}
                <h2 class="global_title text-center">Submit your request</h2>
                <div class="row">
                    <div class="col-xl-8 col-lg-8 col-md-6 col-xs-12">
                        <div class="form_block">
                            {!! Form::label('Project Street Address') !!}
                            {!! Form::text('project_address', null, ['class' => 'field', 'placeholder' => 'Enter Project Street Address', 'required' => true, 'id' => 'project_address', 'data-parsley-trigger' => 'blur']) !!}
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-xs-12">
                        <div class="form_block">
                            {!! Form::label('Zipcode') !!}
                            {!! Form::text('zipcode', null, ['class' => 'field', 'id' => 'zipcode', 'placeholder' => 'Enter Zipcode', 'data-toggle' => 'input-mask', 'data-mask-format' => '00000', 'required' => true, 'data-parsley-trigger' => 'blur']) !!}
                        </div>
                    </div>

                    <div class="col-xl-12 col-lg-12 col-md-12 col-xs-12">
                        <div class="form_block">
                            {!! Form::label('Project Info') !!}
                            {!! Form::textarea('content', null, ['class' => 'field field_2', 'placeholder' => 'Project Info', 'required' => false]) !!}
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                        <div class="form_block">
                            {!! Form::label('Full Name') !!}
                            {!! Form::text('full_name', null, ['class' => 'field', 'placeholder' => 'Enter Full Name', 'required' => true, 'data-parsley-trigger' => 'blur']) !!}
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                        <div class="form_block">
                            {!! Form::label('Email') !!}
                            {!! Form::email('email', null, ['class' => 'field', 'placeholder' => 'Enter Email', 'required' => true, 'data-parsley-trigger' => 'blur']) !!}
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                        <div class="form_block">
                            {!! Form::label('Phone') !!}
                            {!! Form::text('phone', null, ['class' => 'field', 'placeholder' => 'Enter Phone', 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000', 'required' => true, 'data-parsley-trigger' => 'blur']) !!}
                        </div>
                    </div>
                </div>

                {!! Form::hidden("main_category_id", $selected_main_catgory_id, ['id' => 'main_category_id']) !!}
                {!! Form::hidden("top_level_category_id", null, ['id' => 'top_level_category_id']) !!}
                {!! Form::hidden("service_category_type_id", null, ['id' => 'service_category_type_id']) !!}
                {!! Form::hidden("service_category_id", null, ['id' => 'service_category_id']) !!}
                {!! Form::hidden("timeframe", null, ['id' => 'timeframe']) !!}

                <div class="btn_block">
                    <div class="g-recaptcha" id="lead_generate-recaptcha" data-callback="imNotARobot"
                        data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY')  }}"></div>
                    <div class="clearfix">&nbsp;</div>
                    <button type="submit" class="btn generate_lead_btn" disabled>Submit Request</button>
                    <div class="clearfix">&nbsp;</div>
                    <p>By clicking 'Submit Request' You agree to our <a href="javascript:;" data-toggle="modal"
                            data-target="#termsModal">Terms Of Use</a>.</p>
                    <p>We respect your email privacy.</p>
                    <p style="text-align: left; font-weight: 600;">TCPA Policy</p>
                    <p class="tcpa_policy_text" style="text-align: left;">Clicking the submit request button constitutes
                        your express written consent, without obligation to purchase, to be contacted by prospective service
                        providers, Networx Systems, Inc. and its <a href="https://www.networx.com/third-party-contractors"
                            target="_blank">Trusted Partners</a> (including with pre-recorded messages and through automated
                        means, e.g. auto dialing and text messaging) via telephone, mobile device (including SMS and MMS),
                        and/or email, even if your telephone number is on a corporate, state or the National Do Not Call
                        Registry.</p>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </section>

    @if (isset($testimonial_list) && count($testimonial_list) > 0)
        <!-- Testimonial Sec -->
        <section class="testimonial_sec">
            <div class="container">
                <h2 class="global_title text-center">What Others Are Saying</h2>
                <div class="clearfix"></div>
                <div class="owl-carousel test_slider">
                    @foreach($testimonial_list as $testimonial_item)
                        <div class="item">
                            <div class="test_block jQueryEqualHeight">
                                <div class="top_info">
                                    @if (!is_null($testimonial_item->media))
                                        <figure>
                                            <img src="{{ asset('/uploads/media/fit_thumbs/50x50/' . $testimonial_item->media->file_name) }}"
                                                alt="">
                                        </figure>
                                    @endif
                                    <div class="r_info">
                                        <h4>{{ $testimonial_item->company_name }}</h4>
                                        <?php        /* <div class="rate_info"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div> */ ?>
                                    </div>
                                </div>
                                <p>{!! $testimonial_item->content !!}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Pro Sec -->
    <section class="pro_sec">
        <div class="container" style="
        clear: both;
        height: 100%;">
            <div class="row">
                <div class="col-xl-8 col-lg-8 col-md-8 col-xs-12 order-1">
                    <div class="details" style="margin:0;">
                        <h2 class="global_title">Are You An Experienced Home Improvement Contractor?</h2>
                        <p>Consumers are looking for companies they can trust and there's no better place than The Trust
                            Patrick Referral Network at TrustPatrick.com to find them. Connect with thousands of consumers
                            who Trust Patrick to send them only the best companies.</p>
                        <div class="btn_block">
                            <a href="{{env('APP_URL')}}/get-listed/" class="btn">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12 pro_sec_right_gb">
                    <?php //<figure><img src="{{ asset('/images/find_a_pro/mobile.png') }}" alt=""></figure> ?>
                    &nbsp;
                </div>
            </div>
        </div>
    </section>


    <!-- Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $terms_page->title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left">
                    {!! $terms_page->content !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section ('page_js')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <!-- Owl Stylesheets -->
    <link rel="stylesheet" href="{{ asset('/thirdparty/owl/owl.carousel.min.css') }}">
    <script src="{{ asset('/thirdparty/owl/owl.carousel.min.js') }}"></script>

    <script src="{{ asset('/js/admin/jquery-equal-height.min.js') }}"></script>

    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <!-- Plugins js -->
    <script src="{{ asset('/themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('/themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>
    <script src="{{ asset('/themes/admin/assets/js/pages/form-masks.init.js') }}"></script>
    <script type="text/javascript">
        /*$(window).on('load', function (event) {
         $('.jQueryEqualHeight').jQueryEqualHeight('.top_info');
         $('.jQueryEqualHeight').jQueryEqualHeight('p');
         });
         $(window).on('resize', function (event) {
         $('.jQueryEqualHeight').jQueryEqualHeight('.top_info');
         $('.jQueryEqualHeight').jQueryEqualHeight('p');
         });*/
        var imNotARobot = function () {
            //console.info("Button was clicked");
            $(".generate_lead_btn").attr("disabled", false);
        };

        $(function () {
            var owl = $('.test_slider');
            owl.owlCarousel({
                margin: 0,
                loop: true,
                dots: false,
                nav: false,
                autoplay: true,
                items: 5,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 1
                    },
                    767: {
                        items: 2
                    },
                    992: {
                        items: 3
                    },
                    1000: {
                        items: 3,
                    }
                }
            });


            /* Find A Pro [Start] */
            @if (isset($main_category_item) && !is_null($main_category_item))
                var preSendData = {
                    'maincategory': $("#main_category_id").val(),
                    '_token': '{{ csrf_token() }}'
                };
                service_category_type_selection(preSendData);
            @endif

            $("#main_category_search").autocomplete({
                minLength: 3,
                source: function (request, response) {
                    // Fetch data
                    $.ajax({
                        url: '{{ url("find-a-pro/get-maincategories") }}',
                        type: 'post',
                        dataType: "json",
                        data: {
                            search: request.term,
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                select: function (event, ui) {
                    if (ui.item.value !== "Main category not found.") {
                        // Set selection
                        $('#main_category_search').val(ui.item.label); // display the selected text
                        $('#main_category_id').val(ui.item.value); // save selected id to input

                        hide_all_div();
                        var sendData = {
                            'maincategory': ui.item.value,
                            '_token': '{{ csrf_token() }}'
                        };
                        service_category_type_selection(sendData);
                    }
                    return false;
                }
            });

            $(".top_level_search").on("click", function () {
                var top_level_category_id = $(this).data('id');

                top_level_top_search(top_level_category_id);

            });

            $(".top_level_top_search").on("change", function () {
                var top_level_category_id = $(this).val();

                top_level_top_search(top_level_category_id);
            });

        /* $(".top_level_top_search").on("click", function () {
            var top_level_category_id = $(this).data("id");
            $("#top_level_category_id").val(top_level_category_id);

            hide_all_div();
            $("#main_category_search, #main_category_id").val('');

            $.ajax({
                url: '{{ url("find-a-pro/get-top-search-main-categories") }}',
            type: 'POST',
                data: {
                'top_level_category_id': top_level_category_id,
                    '_token': '{{ csrf_token() }}',
                },
            success: function (data) {
                if (typeof data.result_type !== 'undefined') {
                    $("#main_categories").show();
                    $("#main_categories").html(data.html);

                    $('html, body').animate({
                        scrollTop: $("#main_categories").offset().top - 70
                    }, 1000);
                } else {
                    Swal.fire({
                        title: data.title,
                        type: data.type,
                        html: data.message,
                        confirmButtonText: "Ok",
                    });
                }
            }
        });
        }); */

        $(document).on("change", ".main_category_selection", function () {
            var maincategory = $(this).val();
            var toplevelcategory = $("#top_level_category_id").val();

            $('#main_category_id').val(maincategory);
            var sendData = {
                'toplevelcategory': toplevelcategory,
                'maincategory': maincategory,
                '_token': '{{ csrf_token() }}'
            };
            service_category_type_selection(sendData);
        });

        $(document).on("click", ".service_category_type_selection", function () {
            var service_category_type_id = $(this).data("id");
            var main_category_id = $("#main_category_id").val();
            var top_level_category_id = $("#top_level_category_id").val();

            $("#timeframe_selection_div, #find_a_pro_form_div").fadeOut('slow');
            $("#find_a_pro_form_div .form_sec .row input, #find_a_pro_form_div .form_sec .row textarea").val('');
            $("#service_category_id, #timeframe").val('');


            $("#service_category_type_id").val(service_category_type_id);

            $.ajax({
                url: '{{ url("find-a-pro/get-servicecategories") }}',
                type: 'POST',
                data: {
                    'toplevelcategory': top_level_category_id,
                    'maincategory': main_category_id,
                    'servicecategorytype': service_category_type_id,
                    '_token': '{{ csrf_token() }}'
                },
                success: function (data) {
                    if (typeof data.result_type !== 'undefined') {
                        $("#service_categories").show();
                        $("#service_categories").html(data.html);

                        $('html, body').animate({
                            scrollTop: $("#service_categories").offset().top - 70
                        }, 1000);
                    } else {
                        Swal.fire({
                            title: data.title,
                            type: data.type,
                            html: data.message,
                            confirmButtonText: "Ok",
                        });
                    }
                }
            });
            return false;
        })

        $(document).on("change", ".service_category_selection", function () {
            var service_category_id = $(this).val();
            $("#service_category_id").val(service_category_id);

            $("#timeframe_selection_div").show();
            $('html, body').animate({
                scrollTop: $("#timeframe_selection_div").offset().top - 70
            }, 1000);
        });

        $(document).on("change", ".select_timeframe", function () {
            var timeframe = $(this).val();
            $("#timeframe").val(timeframe);

            $("#find_a_pro_form_div").show();
            $('html, body').animate({
                scrollTop: $("#find_a_pro_form_div").offset().top - 70
            }, 1000);
        });

        $(document).on("click", ".previous_section", function () {
            var parent_div = $(this).parents(".service_sec").attr("id");

            if (parent_div == 'service_categories') {
                $("#service_categories, #timeframe_selection_div, #find_a_pro_form_div").fadeOut('slow');
                $("#service_categories .service_category_selection").prop("checked", false);
                $("#find_a_pro_form_div .form_sec .row input, #find_a_pro_form_div .form_sec .row textarea").val('');
                $("#service_category_id, #timeframe").val('');

                $('html, body').animate({
                    scrollTop: $("#service_category_types").offset().top - 70
                }, 1000);
            } else if (parent_div == 'main_categories') {
                $("#main_categories, #service_category_types, #service_categories, #timeframe_selection_div, #find_a_pro_form_div").fadeOut('slow');
                $("#main_categories .main_category_selection, #service_categories .service_category_selection").prop("checked", false);
                $("#find_a_pro_form_div .form_sec .row input, #find_a_pro_form_div .form_sec .row textarea").val('');
                $("#main_category_id, #service_category_type_id, #service_category_id, #timeframe").val('');

                $('html, body').animate({
                    scrollTop: $(".cat_section").offset().top - 70
                }, 1000);
            } else if (parent_div == 'service_category_types') {
                $("#service_category_types, #service_categories, #timeframe_selection_div, #find_a_pro_form_div").fadeOut('slow');
                $("#service_categories .service_category_selection").prop("checked", false);
                $("#find_a_pro_form_div .form_sec .row input, #find_a_pro_form_div .form_sec .row textarea").val('');
                $("#service_category_type_id, #service_category_id, #timeframe").val('');

                var main_category_search = $("#main_category_search").val();
                if (typeof main_category_search !== 'undefined' && main_category_search != '') {
                    $('html, body').animate({
                        scrollTop: $(".hero_sec").offset().top - 70
                    }, 1000);
                } else {
                    $('html, body').animate({
                        scrollTop: $("#main_categories").offset().top - 70
                    }, 1000);
                }
            } else {
                $("#timeframe_selection_div, #find_a_pro_form_div").fadeOut('slow');
                $("#timeframe_selection_div .select_timeframe").prop("checked", false);
                $("#find_a_pro_form_div .form_sec .row input, #find_a_pro_form_div .form_sec .row textarea").val('');
                $("#timeframe").val('');

                $('html, body').animate({
                    scrollTop: $("#service_categories").offset().top - 70
                }, 1000);
            }
        });

        $('.start_over').click(function () {
            $('html, body').animate({
                scrollTop: 0
            }, 1000);
        });

        /*$(".tcpa_policy").on("click", function () {
            $(".tcpa_policy_text").toggle();
        });*/

        window.addEventListener('message', function (event) {
            const data = event.data;
            const { source, payload } = data;
            if (source === "networx" && payload.leadDone) {
                console.log('This was all done!');
                Swal.close();
                window.location.href = window.location.href;
            }
        });

        $(document).on("submit", "#find_a_pro_form", function () {

            var form_url = $(this).attr("action");

            $(".generate_lead_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
            $(".generate_lead_btn").attr('disabled', true);

            $("html").css({
                'background': 'url({{ asset("/images/find_a_pro/processing.gif") }}) no-repeat center center fixed #fff',
                '-webkit-background-size': '50%',
                '-moz-background-size': '50%',
                '-o-background-size': '50%',
                'background-size': '50%',
                'height': '100%'
            });
            $("body").css({
                'opacity': '0'
            });

            $.ajax({
                url: form_url,
                type: 'POST',
                data: $("#find_a_pro_form").serialize(),
                success: function (data) {
                    console.log(data);
                    if (data.networx_processed) {
                        setTimeout(function () {
                            $("html").css({
                                'background-image': 'none'
                            });

                            $("body").css({
                                'opacity': '1'
                            });

                            $(".generate_lead_btn").html('Submit Request');
                            $(".generate_lead_btn").attr('disabled', false);

                            Swal.fire({
                                title: data.title,
                                type: data.type,
                                html: data.message,
                                confirmButtonText: "Ok",
                                showConfirmButton: false
                            });
                        }, 3000);
                    }
                    else {
                        setTimeout(function () {
                            $("html").css({
                                'background-image': 'none'
                            });

                            $("body").css({
                                'opacity': '1'
                            });

                            $(".generate_lead_btn").html('Submit Request');
                            $(".generate_lead_btn").attr('disabled', false);
                            Swal.fire({
                                title: data.title,
                                type: data.type,
                                html: data.message,
                                confirmButtonText: "Submit",
                                allowOutsideClick: false,
                                customClass: {
                                    popup: 'fullscreen-popup'
                                },
                                showLoaderOnConfirm: true,
                                preConfirm: () => {
                                    return new Promise((resolve) => {
                                        let correlation_id = $('input[name="correlation_id"]').val();
                                        let recommended_companies = [];
                                        $('.rec-comp-checkbox:checked').each(function () {
                                            recommended_companies.push($(this).val());
                                        });
                                        if (recommended_companies.length > 0) {
                                            $.ajax({
                                                _token: "{{ csrf_token() }}",
                                                url: "find-a-pro/generate-lead-by-recommened-members",
                                                type: 'POST',
                                                data: {
                                                    _token: "{{ csrf_token() }}", // CSRF token for security
                                                    correlation_id: correlation_id,
                                                    recommended_companies: recommended_companies
                                                },
                                                success: function (data) {
                                                    Swal.fire({
                                                        title: "Processing Complete!",
                                                        html: data.message,
                                                        icon: "success",
                                                        confirmButtonText: "OK"
                                                    }).then(() => {
                                                        resolve(); // Now close the original popup
                                                        window.location.href = window.location.href;
                                                    });
                                                }
                                            });
                                        }
                                    });
                                }
                            });
                        }, 1000);
                    }

                }
            });
            return false;
        });
        /* Find A Pro [End] */
    });

        function service_category_type_selection(ajaxCallData) {
            $.ajax({
                url: '{{ url("find-a-pro/get-servicecategorytypes") }}',
                type: 'POST',
                data: ajaxCallData,
                success: function (data) {
                    if (typeof data.result_type !== 'undefined') {
                        $("#top_level_category_id").val(data.top_level_category_id);

                        if (data.result_type == 'service_category_types') {
                            $("#service_category_types").show();
                            $("#service_category_types").html(data.html);

                            $('html, body').animate({
                                scrollTop: $("#service_category_types").offset().top - 70
                            }, 1000);
                        } else if (data.result_type == 'service_categories') {
                            $("#service_categories").show();
                            $("#service_categories").html(data.html);

                            $('html, body').animate({
                                scrollTop: $("#service_categories").offset().top - 70
                            }, 1000);
                        }
                    } else {
                        if (typeof data.responsemessage !== 'undefined') {
                            alert(data.responsemessage);
                        } else {
                            Swal.fire({
                                title: data.title,
                                type: data.type,
                                html: data.message,
                                confirmButtonText: "Ok",
                            });
                        }
                    }
                }
            });
        }

        function hide_all_div(remove_from_hide = '') {
            $("#main_categories, #service_category_types, #service_categories, #timeframe_selection_div, #find_a_pro_form_div").hide();
        }

        function top_level_top_search(top_level_category_id) {
            $("#top_level_category_id").val(top_level_category_id);

            hide_all_div();
            $("#main_category_search, #main_category_id").val('');

            $.ajax({
                url: '{{ url("find-a-pro/get-top-search-main-categories") }}',
                type: 'POST',
                data: {
                    'top_level_category_id': top_level_category_id,
                    '_token': '{{ csrf_token() }}',
                },
                success: function (data) {
                    if (typeof data.result_type !== 'undefined') {
                        $("#main_categories").show();
                        $("#main_categories").html(data.html);

                        $('html, body').animate({
                            scrollTop: $("#main_categories").offset().top - 70
                        }, 1000);
                    } else {
                        Swal.fire({
                            title: data.title,
                            type: data.type,
                            html: data.message,
                            confirmButtonText: "Ok",
                        });
                    }
                }
            });
        }

        /**Trusted Form Certificate */
        (function () {
            var field = "cert_url";
            var provideReferrer = false;
            var invertFieldSensitivity = false;
            var tf = document.createElement("script");
            tf.type = "text/javascript";
            tf.async = true;
            tf.src = "http" + ("https:" == document.location.protocol ? "s" : "") +
                "://api.trustedform.com/trustedform.js?provide_referrer=" + escape(provideReferrer) + "&field=" + escape(
                    field) + "&l=" + new Date().getTime() + Math.random() + "&invert_field_sensitivity=" +
                invertFieldSensitivity;
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(tf, s);
        })();

        function initAutocomplete() {
            //Bill Address autocomplete
            let autocompleteAddressField = document.querySelector("#project_address");
            autocompleteAddress = new google.maps.places.Autocomplete(autocompleteAddressField, {
                componentRestrictions: { country: ["us"] },
                fields: ["address_components"],
                types: ["address"],
            });
            autocompleteAddressField.focus();
            autocompleteAddress.addListener("place_changed", fillInAddress);
        }
        function fillInAddress() {
            const place = autocompleteAddress.getPlace();

            for (const component of place.address_components) {
                const componentType = component.types[0];
                switch (componentType) {
                    case "postal_code": {
                        document.querySelector("#zipcode").value = component.long_name;
                        break;
                    }
                }
            }
        }
        window.initAutocomplete = initAutocomplete;  
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{ env('GOOGLE_MAP_API_KEY') }}&callback=initAutocomplete&libraries=places&v=weekly"></script>
@endsection