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
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/wp-style-custom.css') }}" />
    @yield('page_css')
</head>
<body>
    <div id="wrapper">
        @include ('company.includes.wp-header-no-menu')    
        
        <div class="content-page no-sidebar">
            <div class="content">
                <div class="container">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    
    @include ('company.includes.wp-footer')
    <!-- End wrapper -->

    @include ('admin.includes._js')
    @include ('company.includes.sticky_js')
    @yield('page_js')

    <!-- Summernote css -->
    <link href="{{ asset('/themes/admin/assets/libs/summernote/summernote-bs4.css') }}" rel="stylesheet" type="text/css" />
    <!-- Summernote js -->
    <script src="{{ asset('/themes/admin/assets/libs/summernote/summernote-bs4.min.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            $(".summernote").summernote({
                height: 250,
                minHeight: null,
                maxHeight: null,
                focus: !1,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ol', 'ul', 'paragraph', 'height']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['undo', 'redo', 'fullscreen', 'codeview', 'help']]
                ]
            });

            $("#member_resources_link").on("click", function (){
                var btn_link = $(this).attr("href");
                var title = text = html = "";
                var type = "error";

                @if (isset($company_item) && $company_item->status != 'Active')
                type = "error";
                html = "Oops. This content is for <strong class='text-info'>Members</strong> only. <br /> <a href='{{ url("referral-list/full-listing-more") }}' class='text-primary'>Explore Upgrade Options</a>";
                
                Swal.fire({
                    title: title,
                    type: type,
                    html: html
                });
                return false;
                @endif
            });

            $("#partner_links_link").on("click", function (){
                var btn_link = $(this).attr("href");
                var title = text = html = "";
                var type = "info";

                @if (isset($company_item) && $company_item->status != 'Active')
                type = "error";
                html = "Oops. This content is for <strong class='text-info'>Members</strong> only. <br /> <a href='{{ url("referral-list/full-listing-more") }}' class='text-primary'>Explore Upgrade Options</a>";

                Swal.fire({
                    title: title,
                    type: type,
                    html: html
                });
                return false;
                @endif
            });
            
            
            $(".paid_pending_cls").on("click", function (){
                @if (isset($company_item) && $company_item->status == 'Paid Pending')
                var type = "warning";
                var title = "You have not completed your application";
                var html = "Please click below to complete your application.";

                Swal.fire({
                    title: title,
                    type: type,
                    html: html,
                    confirmButtonText: "Submit Your Application",
                }).then(function (t){
                    console.log (t);
                    if (typeof t.value !== 'undefined') {
                        window.location.href = '{{ url("account/application") }}';
                    }
                });
                return false;
                @endif
            });
        });
        
        $(window).scroll(function () {
            /*if ($(this).scrollTop() > 1) {
                $('header').addClass("sticky");
            } else {
                $('header').removeClass("sticky");
            }*/
        });
    </script>
</body>
</html>
