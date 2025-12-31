<script type="text/javascript">
    $(function(){

        $(document).on('click', '.img-del-btn', function () {
              var media_id = $(this).data('id');
              var btn = $(this);


              swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4fa7f3',
                cancelButtonColor: '#d57171',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {

                    $.ajax({
                        type: 'post',
                        url: '{{ route("delete-media") }}',
                        data: {"_token": "{{ csrf_token() }}", media_id: media_id},
                        success: function (data) {
                            swal(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                    );
                                    btn.closest('.media_box').hide().remove();
                        },
                        error: function (e) {
                            swal(
                                    'Error!',
                                    'Error while deleting your image, Please try again',
                                    'warning'
                                    )
                        }
                    });


                }
            });

          });

    });
</script>
