<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('.select2').select2();
        $('#top_level_category_id').change(function(){
            $('#main_categories').html('');
            $.ajax({
                type: 'post',
                url: '{{ url("admin/main_categories/get_options") }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "top_level_category_id": $(this).val(),
                },
                success: function(data){
                    $('#main_category_id').html(data);
                            $('#main_category_id').trigger('change');
                    },
                error: function(){
                    alert ('error');
                },
            });
        });
        
        
        //$('#top_level_category_id').trigger('change');
        @if (!Request::is('admin/service_categories'))
        $('#main_category_id').change(function(){
            $.ajax({
                type: 'post',
                url: '{{ url("admin/main_categories/get_ppl_price") }}',
                data: {
                "_token": "{{ csrf_token() }}",
                        "main_category_id": $(this).val(),
                },
                success: function(data){
                    $('#ppl_price').val(data.main_category_item_ppl_price);
                    $.toast({
                        heading: 'Success',
                        text: 'Price of PPL has been changed based on Main Category',
                        icon: 'info',
                        loader: true, // Change it to false to disable loader
                        showHideTransition: 'slide',
                        position: 'bottom-right',
                        loaderBg: '#9EC600'  // To change the background
                    });
                },
                error: function(){
                    alert ('error');
                },
            });
        });
        @endif


        $(".change_scid").on("click", function (){
            var category_id = $(this).data("id");
            var service_category_id = $(this).parents("tr").find(".service_category_id_text").text();
            var service_category_name = $(this).parents("tr").find(".service_category_name").text();
            
            $("#updateSCIDModal #update_service_category_id #category_id").val(category_id);
            $("#updateSCIDModal #update_service_category_id #service_category_id").val(service_category_id);
            $("#updateSCIDModal #service_category_name_text").text(service_category_name);
        });
        
        
        $("#networx_task_id").on("blur", function (){
            var task_id = $(this).val();
            
            if (typeof task_id !== 'undefined' && task_id != ''){
                $.ajax({
                    url: '{{ url("admin/networx_tasks/get-task-detail") }}',
                    type: 'POST',
                    data: {'task_id': task_id, '_token': '{{ csrf_token() }}'},
                    success: function (data){
                        if (data.success == 1){
                            $("#networx_task_name").val(data.task_name);
                        } else {
                            Swal.fire({
                                title: data.title,
                                type: data.type,
                                text: data.message
                            });
                        }
                    }
                });
            }
        });
        
        $(".change_networx").on("click", function (){
            var category_id = $(this).data("id");
            var networx_task_id = $(this).parents("tr").find(".networx_task_id_text").text();
            var networx_task_name = $(this).parents("tr").find(".networx_task_name_text").text();
            
            $("#networxModal #update_networx_details #category_id").val(category_id);
            $("#networxModal #update_networx_details #networx_task_id").val(networx_task_id);
            $("#networxModal #update_networx_details #networx_task_name").val(networx_task_name);
            
        });
    });
</script>
