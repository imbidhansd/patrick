<link href="{{ asset('/themes/admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet" />
<script src="{{ asset('/themes/admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<script>
    $(function () {
         $('.select2').select2();

         $("#trade_id").change(function (){
            $("#top_level_category_id, #main_category_id").html("");

            $.ajax({
                type: 'post',
                url: '{{ url("admin/top_level_categories/get_options") }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "trade_id": $(this).val()
                },
                success: function (data){
                    $('#top_level_category_id').html(data);
                    $('#top_level_category_id').prepend(new Option('All', '', true, true));
                },
                error: function(){
                    alert ('error');
                },
            });
         });

         $('#top_level_category_id').change(function(){
            $('#main_category_id').html('');

            $.ajax({
                type: 'post',
                url: '{{ url("admin/main_categories/get_options") }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "top_level_category_id": $(this).val(),
                },
                success: function(data){
                    $('#main_category_id').html(data);
                    $('#main_category_id').prepend(new Option('All', '', true, true));
                },
                error: function(){
                    alert ('error');
                },
            });

         });
	});
</script>
