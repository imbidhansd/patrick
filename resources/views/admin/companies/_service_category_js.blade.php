<!-- Plugins css -->
<link href="{{ asset('/themes/admin/assets/libs/nestable2/jquery.nestable.min.css') }}" rel="stylesheet"
    type="text/css" />
<!-- Plugins js-->
<script type="text/javascript" src="{{ asset('/themes/admin/assets/libs/nestable2/jquery.nestable.min.js') }}"></script>
<!-- Nestable init-->
<script type="text/javascript" src="{{ asset('/themes/admin/assets/js/pages/nestable.init.js') }}"></script>
<script type="text/javascript">

    $(function (){
        /* Update item */
        $(".update_item").on("click", function (){
            var item_id = $(this).data("id");
            var item_type = $(this).data("type");
            var item_category_type = $(this).data("category_type");

            if ($(this).hasClass('add_item')){
                $("#update_service_category_form #item_process").val('add_item');
            } else if ($(this).hasClass('remove_item')){
                $("#update_service_category_form #item_process").val('remove_item');
            } else if ($(this).hasClass('delete_item')){
                $("#update_service_category_form #item_process").val('delete_item');
            }
            
            $("#update_service_category_form #item_id").val(item_id);
            $("#update_service_category_form #item_type").val(item_type);
            $("#update_service_category_form #item_category_type").val(item_category_type);

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#ff0000",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, remove it!"
            }).then(function (t) {
                if (typeof t.value !== 'undefined') {
                    $('#update_service_category_form').submit();
                }
            });
        });
    });
</script>