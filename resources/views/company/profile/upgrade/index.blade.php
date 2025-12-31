<?php
    $admin_page_title = 'Upgrade Account';
?>
@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')
@include('flash::message')

<!-- Basic Form Wizard -->

<div class="card-box">
    <h1 class="text-center">{{ $admin_page_title }}</h1>
    <div class="clearfix">&nbsp;</div>
    <div class="clearfix">&nbsp;</div>

    @include('admin.includes.formErrors')


    <?php //dd($company_item->toArray()) ?>

    <div class="row">
        <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-10 offset-sm-1">
            <ul class="progress_new">
                <?php $count=0; ?>
                @if ($company_item->membership_level_id != '3')
                <?php $count++; ?>
                <li id="step_dot_{{ $count }}" data-step="{{ $count }}" class="progress_new_step progress_new__circle progress_new--active" title="Choose Your Plan"></li>
                <li id="step_bar_{{ $count }}" class="progress_new_step progress_new__bar"></li>
                @endif
                <?php $count++; ?>
                <li id="step_dot_{{ $count }}" data-step="{{ $count }}" class="progress_new_step progress_new__circle" title="Company Ownership"></li>
                <li id="step_bar_{{ $count }}" class="progress_new_step progress_new__bar"></li>
                <?php $count++; ?>
                <li id="step_dot_{{ $count }}" data-step="{{ $count }}" class="progress_new_step progress_new__circle" title="Type Of Bussiness"></li>
                <li id="step_bar_{{ $count }}" class="progress_new_step progress_new__bar"></li>
                <?php $count++; ?>
                <li id="step_dot_{{ $count }}" data-step="{{ $count }}" class="progress_new_step progress_new__circle" title="Main Service Category"></li>
                <li id="step_bar_{{ $count }}" class="progress_new_step progress_new__bar"></li>
                <?php $count++; ?>
                <li id="step_dot_{{ $count }}" data-step="{{ $count }}" class="progress_new_step progress_new__circle" title="Secondary Service Category"></li>
                <li id="step_bar_{{ $count }}" class="progress_new_step progress_new__bar"></li>
                <?php $count++; ?>
                <li id="step_dot_{{ $count }}" data-step="{{ $count }}" class="progress_new_step progress_new__circle" title="Additional Service Categories"></li>
                <li id="step_bar_{{ $count }}" class="progress_new_step progress_new__bar"></li>
                <?php $count++; ?>
                <li id="step_dot_{{ $count }}" data-step="{{ $count }}" class="progress_new_step progress_new__circle" title="Zipcode Radius"></li>
            </ul>
        </div>
    </div>

    <div class="clearfix">&nbsp;</div>

    <section class="register-steps slider">
        @if ($company_item->membership_level_id != '3')
         <div class="item">
            @include ('company.profile.upgrade.step1')
        </div>
        @endif
        <div class="item">
            @include ('company.profile.upgrade.step2')
        </div>
        <div class="item">
            @include ('company.profile.upgrade.step3')
        </div>
        <div class="item">
            @include ('company.profile.upgrade.step4')
        </div>
        <div class="item">
            @include ('company.profile.upgrade.step5')
        </div>
        <div class="item">
            @include ('company.profile.upgrade.step6')
        </div>
        <div class="item">
            @include ('company.profile.upgrade.step7')
        </div>
    </section>

    <div class="clearfix">&nbsp;</div>
</div>


<!-- Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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

<!-- Slick -->

<link rel="stylesheet" type="text/css" href="{{ asset('thirdparty/slick/slick.css') }}">
<script src="{{ asset('thirdparty/slick/slick.js') }}" type="text/javascript" charset="utf-8"></script>

<!-- Plugins js -->
<script src="{{ asset('/themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>

<!-- Init js-->
<script src="{{ asset('/themes/admin/assets/js/pages/form-masks.init.js') }}"></script>

<script type="text/javascript">
    function setActiveStep(step_num){
        $('.progress_new_step').removeClass('progress_new--done').removeClass('progress_new--active').tooltip('hide');
        for (i=1; i<step_num; i++){
            $('#step_dot_' + i).addClass('progress_new--done progress_new--active');
            $('#step_bar_' + i).addClass('progress_new--done');
        }
        $('#step_dot_' + step_num).addClass('progress_new--active');
        $('#step_dot_' + step_num).attr('data-toggle', 'tooltip').attr('data-placement', 'top').tooltip({trigger: 'manual'}).tooltip('show');
    }

    function refresh_slick_content(){
        setTimeout(function(){
            $(".register-steps").slick('setOption', {}, true);
        }, 100);
        update_main_category_checkbox();
    }
    function slick_next(){
        $(".register-steps").slick('slickNext');
    }
    function slick_slide_to(num){
        $(".register-steps").slick('slickGoTo', num);
    }


    function update_main_category_checkbox(){
        $('.service_category_item_list').each(function(){
            var flag = false;

            $(this).find('.chk_service_cat:checked').each(function(){
                flag = true;
            });

            if (flag == false){
                $(this).closest('.chk_main_cat').find('.chk_all').removeAttr('checked');
            }
        });
    }


    $(function(){

        $(document).on('click', '.progress_new__circle.progress_new--done', function(){
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
            touchMove:false,
            swipe:false,
            accessibility: false,
        });

        // On before slide change
        $('.register-steps').on('afterChange', function(slick, currentSlide){
            step_num = parseInt($('.register-steps').slick('slickCurrentSlide')) + 1;
            setActiveStep(step_num);
            refresh_slick_content();
        });
        $('.register-steps').on('beforeChange', function(slick, currentSlide){
            var top = $('.progress_new').offset().top - ($('#topnav').height() + 50)
                        $('html, body').animate({
                            scrollTop: top
                        }, 800);
        });

        $('.back_btn').click(function(){
            $(".register-steps").slick('slickPrev');
        });

        $('.last_input, .current_step_submit_btn').on('keydown', function(e) {
            if (e.keyCode == 9) {
                $(this).focus();
               e.preventDefault();
            }
        });


        $(document).on('click', '.chk_all', function() {
            $(this).closest('.checkbox').find('input[type="checkbox"]').prop('checked', $(this).is(':checked'));
        });
        $(document).on('click', '.chk_service_cat', function() {
            $(this).closest('.chk_main_cat').find('input[type="checkbox"]:first').prop('checked', true);
        });


        // To Disable Enter Key [Start]
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
        // To Disable Enter Key [End]

    });
</script>

@stack('page_scripts')


<!-- Plugins js -->
<script src="{{ asset('themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>
<script src="{{ asset('themes/admin/assets/js/pages/form-masks.init.js') }}"></script>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{ env('GOOGLE_MAP_API_KEY') }}"></script>
<script src="{{ asset('js/zipcode-radius.js') }}"></script>

@endsection
