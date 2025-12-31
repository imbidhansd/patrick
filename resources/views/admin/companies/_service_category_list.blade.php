<?php /* <h4 class="text-primary">Active Service Categories</h4> */ ?>
<div class="row">
    @if (isset($company_service_category_list) && count($company_service_category_list) > 0)
    @include('admin.companies._service_categories_display')
    @endif
</div>

@if (isset($removed_company_service_category_list) && count($removed_company_service_category_list) > 0)
<hr/>
<h4 class="text-danger">Inactive Service Categories</h4>
<div class="row">
    @php
    $company_service_category_list = $removed_company_service_category_list;
    @endphp
    @include('admin.companies._service_categories_display', ['removed' => 'yes'])
</div>
@endif

@php
$form_url = "update-service-category";
if (isset($admin_form) && $admin_form){
$form_url = "admin/companies/update-service-category";
}
@endphp

{!! Form::open(['url' => url($form_url), 'id' => 'update_service_category_form']) !!}

{!! Form::hidden('company_id', $company_item->id) !!}
{!! Form::hidden('item_id', 0, ['id' => 'item_id']) !!}
{!! Form::hidden('item_type', 0, ['id' => 'item_type']) !!}
{!! Form::hidden('item_category_type', 0, ['id' => 'item_category_type']) !!}
{!! Form::hidden('item_process', 0, ['id' => 'item_process']) !!}

{!! Form::close() !!}


@push('_edit_company_profile_js')
<!-- Plugins css -->
<link href="{{ asset('/themes/admin/assets/libs/nestable2/jquery.nestable.min.css') }}" rel="stylesheet"
    type="text/css" />
<!-- Plugins js-->
<script type="text/javascript" src="{{ asset('/themes/admin/assets/libs/nestable2/jquery.nestable.min.js') }}"></script>
<script type="text/javascript">

    $(function (){
        /* Update item */
        $(".update_item").on("click", function (){
            var item_id = $(this).data("id");
            var item_type = $(this).data("type");
            var item_category_type = $(this).data("category_type");

            var swl_text = "";
            var swl_btn_color = "#ff0000";
            if ($(this).hasClass('add_item')){
                swl_btn_color = "#188ae2";
                $("#update_service_category_form #item_process").val('add_item');
                swl_text = "Are you sure you would like to restore this service category?";
            } else if ($(this).hasClass('remove_item')){
                $("#update_service_category_form #item_process").val('remove_item');
                swl_text = "Are you sure you would like to remove this service category?";
            } else if ($(this).hasClass('delete_item')){
                $("#update_service_category_form #item_process").val('delete_item');
                swl_text = "Are you sure you would like to permanently delete this service category?";
            }
            
            $("#update_service_category_form #item_id").val(item_id);
            $("#update_service_category_form #item_type").val(item_type);
            $("#update_service_category_form #item_category_type").val(item_category_type);

            Swal.fire({
                text: swl_text,
                type: "question",
                showCancelButton: !0,
                confirmButtonColor: swl_btn_color,
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes",
                cancelButtonText: "No"
            }).then(function (t) {
                if (typeof t.value !== 'undefined') {
                    $('#update_service_category_form').submit();
                }
            });
        });
        
        
        $(".service_category_price_update_btn").on("click", function (){
            var old_price = $(this).parents(".cat_name").find(".service_category_fee").text();
            var service_category_id = $(this).data("service_category_id");
            
            $("#updateServicePriceModal #fee").val(old_price);
            $("#updateServicePriceModal #service_category_id").val(service_category_id);
        });
        
        $("#update_service_category_price_form").on("submit", function (){
            var form_link = $(this).attr("action");
            var service_category_id = $("#update_service_category_price_form #service_category_id").val();
            $("#update_service_category_price_form #submit_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
            $("#update_service_category_price_form #submit_btn").attr('disabled', true);
            
            $.ajax({
                url: form_link,
                type: 'POST',
                data: $(this).serialize(),
                success: function (data){
                    $("#update_service_category_price_form #submit_btn").html('Submit');
                    $("#update_service_category_price_form #submit_btn").attr('disabled', false);
            
                    $("#updateServicePriceModal").modal("hide");
                    
                    if (data.status == 1){
                        $("#"+service_category_id+" .service_category_fee").text(parseFloat(data.fee).toFixed(2));
                    }
                    
                    Swal.fire({
                        text: data.title,
                        type: data.type,
                        html: data.message
                    });
                }
            });
            
            return false;
        });
    });
</script>
@endpush