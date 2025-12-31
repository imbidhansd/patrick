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

    @if (!Auth::guard('company_user')->check())
    <!-- Admin Custom Css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/wp-style-custom.css') }}" />
    @endif


    @yield('page_css')
</head>
<body>
    <div id="wrapper">
        @if (Auth::guard('company_user')->check())
        @include ('company.includes.navbar')
        @include ('company.includes.sidebar')
        @else
        @include ('company.includes.wp-header')
        @endif

        <div class="content-page {{ !Auth::guard('company_user')->check() ? 'full-width' : '' }} {{ Session::has('company_mask') && Session::get('company_mask') == true && isset($company_item) ? 'masquerade_mode' : '' }}">
            <div class="content mt-3">
                <div class="{{ Request::is(['preview-trial','accreditation', 'full-listing']) ? 'container' : 'container-fluid' }}">
                    @yield('content')
                </div>
            </div>

            @if (Auth::guard('company_user')->check())
            @include ('admin.includes.footer')
            @endif
        </div>
    </div>


    @if (!Auth::guard('company_user')->check())
        @include ('company.includes.wp-footer')
    @endif

    <!-- End wrapper -->

    @include ('admin.includes._js')
    @yield('page_js')

    <!-- Summernote css -->
    <link href="{{ asset('/themes/admin/assets/libs/summernote/summernote-bs4.css') }}" rel="stylesheet" type="text/css" />
    <!-- Summernote js -->
    <script src="{{ asset('/themes/admin/assets/libs/summernote/summernote-bs4.min.js') }}"></script>
    <?php /*
    <!-- Summernote Cleaner js -->
    <script src="{{ asset('/js/summernote-cleaner.js') }}"></script>
    */ ?>
    <script type="text/javascript">
        $(function () {
            $(".summernote").summernote({
                height: 250,
                minHeight: null,
                maxHeight: null,
                focus: !1,
                toolbar: [
                    //['cleaner',['cleaner']], // The Button
                    //['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                    //['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    //['color', ['color']],
                    ['para', ['ol', 'ul', 'paragraph', 'height']],
                    ['table', ['table']],
                    //['insert', ['link']],
                    ['view', ['undo', 'redo', 'fullscreen', 'codeview', 'help']]
                ],
                /*cleaner:{
                    action: 'both', // both|button|paste 'button' only cleans via toolbar button, 'paste' only clean when pasting content, both does both options.
                    newline: '<br>', // Summernote's default is to use '<p><br></p>'
                    notStyle: 'position:absolute;top:0;left:0;right:0', // Position of Notification
                    icon: '<i class="note-icon">Clean</i>',
                    keepHtml: true, // Remove all Html formats
                    keepOnlyTags: ['<p>', '<br>', '<ul>', '<li>', '<b>', '<strong>','<i>', '<a>'], // If keepHtml is true, remove all tags except these
                    keepClasses: false, // Remove Classes
                    badTags: ['style', 'script', 'applet', 'embed', 'noframes', 'noscript', 'html'], // Remove full tags with contents
                    badAttributes: ['style', 'start'], // Remove attributes from remaining tags
                    limitChars: false, // 0/false|# 0/false disables option
                    limitDisplay: 'both', // text|html|both
                    limitStop: false // true/false
                }*/
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
                @endif 


                @if(isset($company_item) && $company_item->status == 'Pending Approval')
                type = "error";
                html = "Oops. This content is for <strong class='text-info'>Members</strong> only. <br /> You will have access to this page upon approval.";
                Swal.fire({
                    title: title,
                    type: type,
                    html: html
                });
                return false;
                @elseif (isset($company_item) && $company_item->status != 'Active')
                type = "error";
                html = "Oops. This content is for <strong class='text-info'>Members</strong> only. <br /> <a href='{{ url("referral-list/full-listing-more") }}' class='text-primary'>Explore Upgrade Options</a>";
                Swal.fire({
                    title: title,
                    type: type,
                    html: html
                });
                return false;
                @endif */
    
    
                @php
                    $statusArr = ['Approved', 'Final Review', 'Pending Approval', 'Paid Pending'];
                @endphp
                @if (isset($company_item) && ($company_item->membership_level->paid_members == 'no' || $company_item->status == 'Expired'))
                    var upgrade_link = '{{ url("referral-list/full-listing-more") }}';
                                        
                    type = "error";
                    html = "Oops. This content is for <strong class='text-info'>Members</strong> only. <br /> <a href='"+upgrade_link+"' class='text-primary'>Explore Upgrade Options</a>";
                    Swal.fire({
                        title: title,
                        type: type,
                        html: html
                    });
                    return false;
                @elseif (isset($company_item) && in_array($company_item->status, $statusArr))
                    type = "error";
                    html = "Oops. This content is for <strong class='text-info'>Members</strong> only. <br /> You will have access to this page upon approval.";
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
                @endif

                @if(isset($company_item) && $company_item->status == 'Pending Approval')
                type = "error";
                html = "Oops. This content is for <strong class='text-info'>Members</strong> only. <br />You will have access to this page upon approval.";
                Swal.fire({
                    title: title,
                    type: type,
                    html: html
                });
                return false;
                @elseif (isset($company_item) && $company_item->status != 'Active')
                type = "error";
                html = "Oops. This content is for <strong class='text-info'>Members</strong> only. <br /> <a href='{{ url("referral-list/full-listing-more") }}' class='text-primary'>Explore Upgrade Options</a>";
                Swal.fire({
                    title: title,
                    type: type,
                    html: html
                });
                return false;
                @endif */
    
    
                @php
                    $statusArr = ['Approved', 'Final Review', 'Pending Approval', 'Paid Pending'];
                @endphp
                @if (isset($company_item) && ($company_item->membership_level->paid_members == 'no' || $company_item->status == 'Expired'))
                    var upgrade_link = '{{ url("referral-list/full-listing-more") }}';
                                        
                    type = "error";
                    html = "Oops. This content is for <strong class='text-info'>Members</strong> only. <br /> <a href='"+upgrade_link+"' class='text-primary'>Explore Upgrade Options</a>";
                    Swal.fire({
                        title: title,
                        type: type,
                        html: html
                    });
                    return false;
                @elseif (isset($company_item) && in_array($company_item->status, $statusArr))
                    type = "error";
                    html = "Oops. This content is for <strong class='text-info'>Members</strong> only. <br /> You will have access to this page upon approval.";
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
                    confirmButtonColor: "#f5707a",
                }).then(function (t){
                    if (typeof t.value !== 'undefined') {
                        window.location.href = '{{ url("account/application") }}';
                    }
                });
                return false;
                @endif
            });

            $(".dismiss_video").on("click", function () {
                Swal.fire({
                    title: "Are you sure to dismiss this video?",
                    //text: "You won't be able to revert this!",
                    type: "question",
                    showCancelButton: !0,
                    confirmButtonColor: "#3db9dc",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, Dismiss it!"
                }).then(function (t) {
                    if (typeof t.value != 'undefined') {
                        $.ajax({
                            context: this,
                            url: '{{ url("dismiss-dashboard-video") }}',
                            type: 'POST',
                            data: {'_token': '{{ csrf_token() }}'},
                            success: function (data){
                                window.location.reload();
                            }
                        });
                    }
                });
            });
        });
    </script>


    @stack('additional_scripts')
</body>

</html>
