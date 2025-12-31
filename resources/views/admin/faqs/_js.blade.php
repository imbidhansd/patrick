<script type="text/javascript">

    $(function (){
        $("#membership_level_id").on("change", function (){
            var membership_level_id = $(this).val();

            if (typeof membership_level_id !== 'undefined' && membership_level_id != ''){
                $.ajax({
                    url: '{{ url("admin/faqs/get-membership-status-from-level") }}',
                    type: 'POST',
                    data: {'membership_level_id': membership_level_id, '_token': '{{ csrf_token() }}'},
                    success: function (data){
                        if (typeof data.success !== 'undefined'){
                            Swal.fire({
                                title: "Error",
                                text: "No status found with selected level.",
                                type: "warning",
                            });
                        } else {
                            $("#membership_status_id").html(data);

                            @if (isset($formObj) && !is_null($formObj->membership_status_id))
                            $("#membership_status_id").val('{{ $formObj->membership_status_id }}');
                            @endif
                        }
                    }
                });
            }
        });

        @if (isset($formObj))
        $("#membership_level_id").trigger("change");
        @endif
    });
</script>