@extends('admin.layout')
@section('title', $admin_page_title)

@section ('content')

@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => $module_urls['list'],
$admin_page_title => '']])
@include('flash::message')

    @include('admin.includes.formErrors')

    <?php /* {!! Form::model($formObj, ['method' => 'PUT', 'route' => [$module_urls['update'], $formObj->id], 'class' =>
    'module_form', 'files' => true,]) !!} */ ?>
    @include($module_urls['form_file'] , ['new_form' => false])
    <?php /* {!! Form::close() !!} */ ?>


@stop

@section('page_js')
@stack('_edit_company_profile_js')
<script type="text/javascript">
    $(function () {
        $(".edit_note").on("click", function () {
            var company_note = $(this).parent().find(".edit_company_note").val();
            var company_note_id = $(this).data("id");

            $("#addNoteModal #company_note").summernote("code", company_note);
            $("#addNoteModal #company_note_id").val(company_note_id);
            $("#addNoteModal").modal("show");
        });
        
        @if(Session::has('active_accordion'))
            var accordion_id = '{{ Session::get("active_accordion") }}';

            $(".card .collapse").removeClass("show");
            $(".card .accordion_link").attr("aria-expanded", false);

            $(".card #"+accordion_id).addClass("show");
            $(".card .accordion_link[aria-controls='+accordion_id+']").attr("aria-expanded", true);

            if (accordion_id != 'member_status'){
                $('html, body').animate({
                    scrollTop: $("#"+accordion_id).parents(".card").offset().top - 80
                }, 2000);
            }
        
            @php Session::forget('active_accordion'); @endphp
        @endif
        
        if (window.location.hash) {
            accordion_id = window.location.hash;
            var area_controls = accordion_id.split('#');
            $(".card .collapse").removeClass("show");
            $(".card .accordion_link").attr("aria-expanded", false);

            $(".card "+accordion_id).addClass("show");
            $(".card .accordion_link[aria-controls='+area_controls[1]+']").attr("aria-expanded", true);
            
            $('html, body').animate({
                scrollTop: 0
            }, 0);
            $('html, body').animate({
                scrollTop: $(accordion_id).parents(".card").offset().top - 80
            }, 2000);
        }
    });
</script>
@stop
