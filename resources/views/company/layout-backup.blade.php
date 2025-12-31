<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('themes/admin/assets/images/favicon.png') }}">
    <!-- App title -->
    <title>@yield('title')</title>

    <!-- App css -->
    @include ('admin.includes._css')


    <?php /*
    @if (!Auth::guard('company_user')->check())
    <!-- Admin Custom Css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/wp-style-custom.css') }}" />
    @endif
    */ ?>

    @yield('page_css')
</head>



<body data-layout="horizontal">

    <div id="wrapper">

        @include ('company.includes.header')

        <?php /*
        @if (Auth::guard('company_user')->check())
        @include ('company.includes.header')
        @else
        @include ('company.includes.wp-header')    
        @endif
        */ ?>
        <div class="content-page">
            <div class="content">
                <div class="{{ Request::is(['preview-trial','accreditation', 'full-listing']) ? 'container' : 'container-fluid' }}">
                    @yield('content')
                </div>
            </div>

            @include ('admin.includes.footer')

            <?php /*
            @if (Auth::guard('company_user')->check())
                
            @endif  
            */ ?>                
        </div>
    </div>

    <?php /*
    @if (!Auth::guard('company_user')->check())
        @include ('company.includes.wp-footer')
    @endif  
    */ ?>    
    <!-- End wrapper -->

    @include ('admin.includes._js')
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

                /*@php
                    $statusArr = [
                        'Active', 'Cancelled', 'Expired', 'Paused', 'Suspended', 'Suspended With Cause'
                    ];
                @endphp

                @if (isset($company_item) && ($company_item->status == 'Approved' || $company_item->status == 'Final Review'))
                title = "";
                type = "warning";
                text = "Oops. This content is for members only.";
                @elseif (isset($company_item) && $company_item->status == 'Pending Approval')
                title = "You're almost there!";
                text = "Once you have been approved, all features of your membership will be unlocked!";
                @elseif (isset($company_item) && $company_item->status == 'Paid Pending')
                title = "You have not completed your application.";
                text = "Please click here to complete your application.";
                @elseif (isset($company_item) && $company_item->status == 'Temporarily Suspended')
                title = "Your listing has been temporarily suspended.";
                text = "Please call support at 720-445-4400";
                @elseif (isset($company_item) && in_array ($company_item->status, $statusArr))
                window.location = btn_link;
                return false;
                @endif */


                @if (isset($company_item) && $company_item->status != 'Active')
                type = "error";
                html = "Oops. This content is for <strong class='text-info'>Members</strong> only. <br /> <a href='#' class='text-primary'>Explore Upgrade Options</a>";
                
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

                /*@php
                    $statusArr = [
                        'Active', 'Cancelled', 'Expired', 'Paused', 'Suspended', 'Suspended With Cause'
                    ];
                @endphp


                @if (isset($company_item) && ($company_item->status == 'Approved' || $company_item->status == 'Final Review'))
                title = "";
                type = "warning";
                text = "Oops. This content is for members only.";
                @elseif (isset($company_item) && $company_item->status == 'Pending Approval')
                title = "You're almost there!";
                text = "Once you have been approved, all features of your membership will be unlocked!";
                @elseif (isset($company_item) && $company_item->status == 'Paid Pending')
                title = "You have not completed your application.";
                text = "Please click here to complete your application.";
                @elseif (isset($company_item) && $company_item->status == 'Temporarily Suspended')
                title = "Your listing has been temporarily suspended.";
                text = "Please call support at 720-445-4400";
                @elseif (isset($company_item) && in_array ($company_item->status, $statusArr))
                window.location = btn_link;
                return false;
                @endif*/

                @if (isset($company_item) && $company_item->status != 'Active')
                html = "Oops. This content is for <strong class='text-info'>Members</strong> only. <br /> <a href='#' class='text-primary'>Explore Upgrade Options</a>";

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
    </script>
</body>

</html>
