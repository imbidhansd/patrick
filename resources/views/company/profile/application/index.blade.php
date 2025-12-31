<?php
$admin_page_title = 'Submit your Application';
?>
@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<?php
$step_2_page_title = 'Licensing And Warranties';
if ($company_item->trade_id == 2) {// Professional
    $step_2_page_title = 'Licensing And Registration';
}
?>

<div class="card-box">
    <h1 class="text-center">{{ $admin_page_title }}</h1>
    <div class="clearfix">&nbsp;</div>
    <div class="clearfix">&nbsp;</div>
    <div class="row">
        <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-10 offset-sm-1">
            <ul class="progress_new">
                <li id="step_dot_1" data-step="1" class="progress_new_step progress_new__circle progress_new--active" title="Company Information"></li>
                <li id="step_bar_1" class="progress_new_step progress_new__bar"></li>
                <li id="step_dot_2" data-step="2" class="progress_new_step progress_new__circle" title="{{ $step_2_page_title }}"></li>
                <li id="step_bar_2" class="progress_new_step progress_new__bar"></li>
                <li id="step_dot_3" data-step="3" class="progress_new_step progress_new__circle" title="Insurance Information"></li>
                <li id="step_bar_3" class="progress_new_step progress_new__bar"></li>
                <li id="step_dot_4" data-step="4" class="progress_new_step progress_new__circle" title="Reference And Professional Affiliations"></li>
                <li id="step_bar_4" class="progress_new_step progress_new__bar"></li>
                <li id="step_dot_5" data-step="5" class="progress_new_step progress_new__circle" title="Company Page And Lead Notifications"></li>
                <li id="step_bar_5" class="progress_new_step progress_new__bar"></li>
                <li id="step_dot_6" data-step="6" class="progress_new_step progress_new__circle" title="Listing Agreement"></li>
            </ul>
        </div>
    </div>
    <div class="clearfix">&nbsp;</div>

    <section class="application-steps slider">
        <div class="item">
            @include ('company.profile.application.step1')
        </div>
        <div class="item" id="item2">
            @include ('company.profile.application.step2')
        </div> 
        <div class="item">
            @if ($company_item->trade_id == 2)
            @include ('company.profile.application.step3_professional')
            @else
            @include ('company.profile.application.step3')
            @endif
        </div> 
        <div class="item">
            @include ('company.profile.application.step4')
        </div> 
        <div class="item">
            @include ('company.profile.application.step5')
        </div>
        <div class="item">
            @include ('company.profile.application.step6')
        </div>
    </section>
</div>

<div class="modal fade" id="warningModal" tabindex="-1" role="dialog" aria-labelledby="warningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle display-4 m-0 text-danger"></i>
                <h3 class="text-danger">Attention</h3>
                <p class="text-danger font-14">ONLY people within your company can be added to receive copies of leads from your listing. Leads CANNOT be sent to anyone outside of your company. For obvious reasons, we do not allow companies to sub-contract work or pass leads to companies that are not approved by TrustPatrick.com. Sending leads to someone outside of your company is strictly against our terms and conditions and doing so will result in an immediate termination of your listing.</p>
            </div>

            <div class="modal-footer text-center">
                <button type="button" class="btn btn-info waves-effect m-0-auto" data-dismiss="modal">I Understand And Agree</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
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

<script>


$(function () {
    $('input[type="file"]').change(function (e) {

        var files = e.target.files[0]
        var fileExtension = files.type.split("/").pop();
        var fileName = files.name

        var validFileExtensions = ['png', 'jpg', 'jpeg', 'tif', 'bmp', 'pdf'];
        //alert (validFileExtensions.includes(fileExtension));

        if (validFileExtensions.includes(fileExtension)) {

            //alert ($(this).data('preview'));
            var preview_div = $('.' + $(this).data('preview'));

            //var html = '<a href="' + URL.createObjectURL(e.target.files[0]) + '" data-fancybox="gallery">';
            var html = '<div class="mt-2">' + files.name + '<br/>';
            if (fileExtension == 'pdf') {
                html += '<i class="fas fa-file-pdf font-50 mt-1"></i>';
            } else {
                html += '<i class="fas fa-file-image font-50 mt-1"></i>';
            }

            html += '<br/><a href="javascript:;" data-id="#' + $(this).attr('id') + '" class="btn btn-xs btn-danger mt-1 rem_file">Remove</a></div>';
            preview_div.html(html);


        } else {
            $(this).val('');
        }


        refresh_slick_content();

    });


    $(document).on('click', '.rem_file', function () {
        var file_elem = $(this).data('id');
        $(file_elem).val('');
        $(this).closest('.preview_file').html('');
        refresh_slick_content();
    });

});

</script>

<script type="text/javascript">
    function setActiveStep(step_num) {
        $('.progress_new_step').removeClass('progress_new--done').removeClass('progress_new--active').tooltip('hide');
        for (i = 1; i < step_num; i++) {
            $('#step_dot_' + i).addClass('progress_new--done progress_new--active');
            $('#step_bar_' + i).addClass('progress_new--done');
        }
        $('#step_dot_' + step_num).addClass('progress_new--active');
        $('#step_dot_' + step_num).attr('data-toggle', 'tooltip').attr('data-placement', 'top').tooltip({trigger: 'manual'}).tooltip('show');
    }

    function refresh_slick_content() {
        $(".application-steps").slick('setOption', {}, true);
    }
    function slick_next() {
        $(".application-steps").slick('slickNext');
    }

    $(function () {

        $(document).on('click', '.progress_new__circle.progress_new--done', function () {
            slideno = parseInt($(this).data('step'));
            $('.application-steps').slick('slickGoTo', slideno - 1);
        });

        setActiveStep(1);

        slick = $(".application-steps").slick({
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
        $('.application-steps').on('afterChange', function (slick, currentSlide) {
            step_num = parseInt($('.application-steps').slick('slickCurrentSlide')) + 1;
            //$('.slick-current').find('input:visible:first').focus();
            //alert (step_num);
            setActiveStep(step_num);
        });
        $('.application-steps').on('beforeChange', function (slick, currentSlide) {
            var top = $('.progress_new').offset().top - ($('#topnav').height() + 20)
            $('html, body').animate({
                scrollTop: 0
            }, 800);
        });

        $('.back_btn').click(function () {
            $(".application-steps").slick('slickPrev');
        });

        $('.last_input, .current_step_submit_btn').on('keydown', function (e) {
            if (e.keyCode == 9) {
                $(this).focus();
                e.preventDefault();
            }
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
