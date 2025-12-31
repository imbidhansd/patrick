<!-- Vendor js -->
<script src="{{ asset('themes/admin/assets/js/vendor.min.js') }}"></script>

<!-- App js -->
<script src="{{ asset('themes/admin/assets/js/app.min.js') }}"></script>


<!-- Parsley js -->
<script src="{{ asset('thirdparty/parsley/parsley.js') }}" type="text/javascript"></script>

<!-- Sweet-Alert  -->
<script src="{{ asset('themes/admin/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<!-- Toastr js -->
<script src="{{ asset('thirdparty/toast/jquery.toast.js') }}"></script>

<!-- Bootstrap File Style JS -->
<script src="{{ asset('themes/admin/assets/libs/bootstrap-filestyle2/bootstrap-filestyle.min.js') }}"></script>

<!-- Max Length JS -->
<script src="{{ asset('themes/admin/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>

<!-- Fancy Box -->
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

<!-- Admin Custom JS -->
<script src="{{ asset('js/admin/custom.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    $(function(){
        /* Copy Button Event */
        $(document).on('click', '.copy_btn', function () {

            var href = $(this).attr('href');
            Swal.fire({
                title: "Are you sure to copy this item?",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#ff9800",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, Copy it!"
            }).then(function (t) {
                if (typeof t.value != 'undefined'){
                    window.location.href = href;
                }
            })
            return false;
        });


        /* Delete Button Event */
        $(document).on('click', '.img-del-btn', function () {
            var media_id = $(this).data('id');
            var setting_id = $(this).data('setting_id');
            var btn = $(this);



            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#ff0000",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, delete it!"
            }).then(function (t) {
                if (typeof t.value != 'undefined'){

                    $.ajax({
                        type: 'post',
                        url: '{{ route("delete-media") }}',
                        data: { "_token": "{{ csrf_token() }}", media_id: media_id, setting_id: setting_id },
                        success: function (data) {
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            );
                            btn.closest('.media_box').hide().remove();
                        },
                        error: function (e) {
                            Swal.fire(
                                'Error!',
                                'Error while deleting your image, Please try again',
                                'warning'
                            )
                        }
                    })

                }
            });
            return false;

        });
    });
</script>
