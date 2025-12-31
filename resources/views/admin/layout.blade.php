<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
    <!-- App title -->
    <title>@yield('title')</title>

    <!-- App css -->
    @include ('admin.includes._css')
    @yield('page_css')
</head>


<body>

    <div id="wrapper">
        <!-- Topbar Start -->
        @include ('admin.includes.navbar')
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        @include ('admin.includes.sidebar')
        <!-- Left Sidebar End -->

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            @include ('admin.includes.footer')
        </div>


    </div>
    <!-- End wrapper -->

    @include ('admin.includes._js')
    @yield('page_js')


    <script type="text/javascript">
        $(function() {
            $("#side-menu a").each(function() {
                var href0 = window.location.href.split(/[?#]/)[0];
                var href1 = window.location.href.split(/[?#]/)[1];

                if (this.href == href0 + "?" + href1) {
                    $(this).addClass("active");
                    $(this).parent().addClass("mm-active");
                    $(this).parent().parent().addClass("mm-show");
                    $(this).parent().parent().prev().addClass("active");
                    $(this).parent().parent().parent().addClass("mm-active");
                    $(this).parent().parent().parent().parent().addClass("mm-show");
                    $(this).parent().parent().parent().parent().parent().addClass("mm-active");

                    return false;
                } else if (this.href == href0) {
                    $(this).addClass("active");
                    $(this).parent().addClass("mm-active");
                    $(this).parent().parent().addClass("mm-show");
                    $(this).parent().parent().prev().addClass("active");
                    $(this).parent().parent().parent().addClass("mm-active");
                    $(this).parent().parent().parent().parent().addClass("mm-show");
                    $(this).parent().parent().parent().parent().parent().addClass("mm-active");

                    return false;
                }
            });


            $(".send_test_email").on("click", function() {
                var type = $(this).data("type");
                var email_id = $(this).data("id");

                Swal.fire({
                    title: 'Are you sure?',
                    type: 'question',
                    text: 'Are you sure to send test email?',
                    showCancelButton: !0,
                    cancelButtonColor: "#ff0000",
                    confirmButtonText: "Send Test Email",
                    confirmButtonColor: "#003E74",
                }).then(function(t) {
                    if (typeof t.value !== 'undefined') {
                        $(this).html('Processing... <i class="fas fa-spinner fa-spin"></i>');
                        $(this).attr('disabled', true);

                        $.ajax({
                            context: this,
                            url: '{{ url('admin/send_test_emails') }}',
                            type: 'POST',
                            data: {
                                'type': type,
                                'email_id': email_id,
                                '_token': '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                $(this).html('<i class="fas fa-envelope"></i>');
                                $(this).attr('disabled', false);

                                Swal.fire({
                                    title: data.title,
                                    type: data.type,
                                    text: data.text
                                });
                            }
                        });
                    }
                });
            });
        });

        /*Search box toggle*/
        $(document).ready(function() {
            var $bgs = $('.box-toggle');
            $('.btn-toggle-section').click(function() {
                var $target = $(`.${$(this).data('target')}`).stop(true).slideToggle();
                $bgs.not($target).filter(':visible').stop(true, true).slideUp();
            });
        });
    </script>


    @stack('additional_scripts')


</body>

</html>
