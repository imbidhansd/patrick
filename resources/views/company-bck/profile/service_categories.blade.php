<?php
    $admin_page_title = 'Service Categories';
?>
@extends('company.layout')


@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="row">

    <div class="col-sm-9">
        <div class="row">
            @if (isset($company_service_category_list) && count($company_service_category_list) > 0)
            @include('company.profile._service_categories_display')
            @endif
        </div>

        <div class="row">
            @if (isset($removed_company_service_category_list) && count($removed_company_service_category_list) > 0)
            @php
            $company_service_category_list = $removed_company_service_category_list;
            @endphp

            @include('company.profile._service_categories_display', ['removed' => 'yes'])
            @endif
        </div>
    </div>

    @include('company.profile._company_profile_sidebar')
</div>


{!! Form::open(['url' => url('update-service-category'), 'id' => 'udpate_service_category_form']) !!}
{!! Form::hidden('item_id', 0, ['id' => 'item_id']) !!}
{!! Form::hidden('item_type', 0, ['id' => 'item_type']) !!}
{!! Form::hidden('item_category_type', 0, ['id' => 'item_category_type']) !!}
{!! Form::hidden('item_process', 0, ['id' => 'item_process']) !!}
{!! Form::close() !!}


@endsection

@section ('page_js')
@include('company.profile._js')
<!-- Plugins css -->
<link href="{{ asset('/themes/admin/assets/libs/nestable2/jquery.nestable.min.css') }}" rel="stylesheet"
    type="text/css" />
<!-- Plugins js-->
<script src="{{ asset('/themes/admin/assets/libs/nestable2/jquery.nestable.min.js') }}"></script>
<!-- Nestable init-->
<script src="{{ asset('/themes/admin/assets/js/pages/nestable.init.js') }}"></script>
<script type="text/javascript">
    $(function (){
        /* Update item */
        $(".update_item").on("click", function (){
            var item_id = $(this).data("id");
            var item_type = $(this).data("type");
            var item_category_type = $(this).data("category_type");

            if ($(this).hasClass('add_item')){
                $("#udpate_service_category_form #item_process").val('add_item');
            } else if ($(this).hasClass('remove_item')){
                $("#udpate_service_category_form #item_process").val('remove_item');
            } else if ($(this).hasClass('delete_item')){
                $("#udpate_service_category_form #item_process").val('delete_item');
            }
            $("#udpate_service_category_form #item_id").val(item_id);
            $("#udpate_service_category_form #item_type").val(item_type);
            $("#udpate_service_category_form #item_category_type").val(item_category_type);

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#ff0000",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, remove it!"
            }).then(function (t) {
                if (typeof t.value != 'undefined') {
                    $('#udpate_service_category_form').submit();
                }
            });
        });
    });
</script>
@endsection
