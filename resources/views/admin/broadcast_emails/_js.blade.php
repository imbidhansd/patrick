<script type="text/javascript">
$(function () {
    @if (isset($list_params['email_type']) && $list_params['email_type'] != '')
    var email_type = '{{ $list_params['email_type'] }}';
    var add_button_link = $(".add_button").attr("href");
    $(".add_button").attr("href", add_button_link+"?email_type="+email_type);

    var reset_link = $(".reset_button").attr("href");
    $(".reset_button").attr("href", reset_link+"?email_type="+email_type);
    @endif

    /* Get Top Level Categories Start */
    $("#trade_id").on("change", function () {
        var trade_id = $(this).val();

        if (typeof trade_id !== 'undefined' && trade_id != '') {
            $.ajax({
                url: '{{ url("admin/broadcast_emails/get-top-level-categories") }}',
                type: 'POST',
                data: {'trade_id': trade_id, '_token': '{{ csrf_token() }}'},
                success: function (data) {
                    $("#top_level_category_id").html(data);
                },
                error: function (data) {
                    alert('error');
                },
            });
        }

    });
    /* Get Top Level Categories End */
    
    
    
    /* Get Top Main Categories Start */
    $(document).on("change", "#top_level_category_id", function (){
        var top_level_category_id = $(this).val();
        
        if (typeof top_level_category_id !== 'undefined' && top_level_category_id != ''){
            $.ajax({
                url: '{{ url("admin/broadcast_emails/get-main-categories") }}',
                type: 'POST',
                data: {'top_level_category_id': top_level_category_id, '_token': '{{ csrf_token() }}'},
                success: function (data) {
                    $("#main_category_id").html(data);
                },
                error: function (data) {
                    alert('error');
                },
            });
        }
    });
    /* Get Top Main Categories End */
    
    
    
    /* Get Top Service Categories Start */
    $(document).on("change", "#main_category_id", function (){
        var main_category_id = $(this).val();
        var top_level_category_id = $("#top_level_category_id").val();
        
        if (typeof main_category_id !== 'undefined' && main_category_id != '' && typeof top_level_category_id !== 'undefined' && top_level_category_id != ''){
            $.ajax({
                url: '{{ url("admin/broadcast_emails/get-service-categories") }}',
                type: 'POST',
                data: {'main_category_id': main_category_id, 'top_level_category_id': top_level_category_id, '_token': '{{ csrf_token() }}'},
                success: function (data){
                    $("#service_category_id").html(data);
                },
                error: function (data){
                    alert ("error");
                }
            });
        }
    });
    /* Get Top Service Categories End */
});
</script>