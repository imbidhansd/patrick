@extends('company.layout-without-menu-and-sidebar')

@section ('content')
@section('title', $admin_page_title)
@include('flash::message')
@include('admin.includes.formErrors')

<!-- Basic Form Wizard -->

<div class="card-box">
    <h1 class="text-center">{{ $admin_page_title }}</h1>
    <div class="clearfix">&nbsp;</div>
    <div class="clearfix">&nbsp;</div>
    <div class="row">
        <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-10 offset-sm-1">
            <ul class="progress_new">
                <li id="step_dot_1" data-step="1" class="progress_new_step progress_new__circle progress_new--active"
                title="Account Information"></li>
                <li id="step_bar_1" class="progress_new_step progress_new__bar"></li>
                <li id="step_dot_2" data-step="2" class="progress_new_step progress_new__circle"
                title="Company Information"></li>
                <li id="step_bar_2" class="progress_new_step progress_new__bar"></li>
                <li id="step_dot_3" data-step="3" class="progress_new_step progress_new__circle"
                title="Type Of Business"></li>
                <li id="step_bar_3" class="progress_new_step progress_new__bar"></li>
                <li id="step_dot_4" data-step="4" class="progress_new_step progress_new__circle"
                title="Main Service Category"></li>
                <li id="step_bar_4" class="progress_new_step progress_new__bar"></li>
                <li id="step_dot_5" data-step="5" class="progress_new_step progress_new__circle"
                title="Secondary Service Category"></li>
                <li id="step_bar_5" class="progress_new_step progress_new__bar"></li>
                <li id="step_dot_6" data-step="6" class="progress_new_step progress_new__circle"
                title="Additional Service Categories"></li>
                <li id="step_bar_6" class="progress_new_step progress_new__bar"></li>
                <li id="step_dot_7" data-step="7" class="progress_new_step progress_new__circle" title="Zipcode Radius">
                </li>
            </ul>
        </div>
    </div>
    <div class="clearfix">&nbsp;</div>

    <section class="register-steps slider">
        <div class="item">
            @include ('company.register.step1')
        </div> 
        <div class="item">
            @include ('company.register.step2')
        </div>
        <div class="item">
            @include ('company.register.step3')
        </div>
        <div class="item">
            @include ('company.register.step4')
        </div>
        <div class="item">
            @include ('company.register.step5')
        </div>
        <div class="item">
            @include ('company.register.step6')
        </div>
        <div class="item">
            @include ('company.register.step7')
        </div>
    </section>

</div>


<!-- Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
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

<!-- Slick -->

<link rel="stylesheet" type="text/css" href="{{ asset('thirdparty/slick/slick.css') }}">
<script src="{{ asset('thirdparty/slick/slick.js') }}" type="text/javascript" charset="utf-8"></script>

<!-- Plugins js -->
<script src="{{ asset('/themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>

<!-- Init js-->
<script src="{{ asset('/themes/admin/assets/js/pages/form-masks.init.js') }}"></script>

<script type="text/javascript">
    function setActiveStep(step_num) {
        $('.progress_new_step').removeClass('progress_new--done').removeClass('progress_new--active').tooltip('hide');
        for (i = 1; i < step_num; i++) {
            $('#step_dot_' + i).addClass('progress_new--done progress_new--active');
            $('#step_bar_' + i).addClass('progress_new--done');
        }
        $('#step_dot_' + step_num).addClass('progress_new--active');
        $('#step_dot_' + step_num).attr('data-toggle', 'tooltip').attr('data-placement', 'top').tooltip({trigger: 'manual'}).tooltip('show');
        $(window).trigger('scroll');
    }



    function refresh_slick_content() {
        $(".register-steps").slick('setOption', {}, true);
    }
    function slick_next() {
        $(".register-steps").slick('slickNext');
    }
    function slick_slide_to(num) {
        $(".register-steps").slick('slickGoTo', num);
    }



    $(function () {

        $('.content-page').css('margin-top', 'auto !important');

        $(document).on('click', '.progress_new__circle.progress_new--done', function () {
            slideno = parseInt($(this).data('step'));
            $('.register-steps').slick('slickGoTo', slideno - 1);
        });

        setActiveStep(1);

        slick = $(".register-steps").slick({
            dots: false,
            prevArrow: false,
            nextArrow: false,
            infinite: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            adaptiveHeight: true,
            touchMove: false,
            swipe: false,
            accessibility: false,
        });

    // On before slide change
    $('.register-steps').on('afterChange', function (slick, currentSlide) {
        step_num = parseInt($('.register-steps').slick('slickCurrentSlide')) + 1;
        setActiveStep(step_num);
    });
    $('.register-steps').on('beforeChange', function (slick, currentSlide) {
        var top = $('.progress_new').offset().top - ($('#topnav').height() + 20)
        $('html, body').animate({
            scrollTop: top
        }, 800);
    });

    $('.back_btn').click(function () {
        $(".register-steps").slick('slickPrev');
    });

    $('.last_input, .current_step_submit_btn').on('keydown', function (e) {
        if (e.keyCode == 9) {
            $(this).focus();
            e.preventDefault();
        }
    });

    $(document).on('click', '.chk_all', function () {
        $(this).closest('.checkbox').find('input[type="checkbox"]').prop('checked', $(this).is(':checked'));
    });
    $(document).on('click', '.chk_service_cat', function () {
        $(this).closest('.chk_main_cat').find('input[type="checkbox"]:first').prop('checked', true);
    });

    // To Disable Enter Key [Start]
    $(window).keydown(function (event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
    // To Disable Enter Key [End]

});
</script>

@stack('page_scripts')

@endsection
