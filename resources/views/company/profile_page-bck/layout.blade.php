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
    
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/wp-profile-page.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/profile_page.css') }}" />
    @yield('page_css')
</head>
<body>
    <div id="wrapper">
        @include ('company.includes.wp-header')    
        
        <div class="content-page no-sidebar">
            <div class="content">
                
                    @yield('content')
                
            </div>
        </div>
    </div>
    
    @include ('company.includes.wp-footer')
    <!-- End wrapper -->

    @include ('admin.includes._js')
    @yield('page_js')
    <script src="{{ asset('/') }}thirdparty/sticky-header/jquery.sticky.min.js"></script>
    <script type="text/javascript">
        $(function () {


            $("header").sticky({topSpacing: -5});

            /*$('.nav-item.dropdown').hover(function () {
                $('.nav-item.dropdown').removeClass('show');
                $('.nav-item.dropdown .dropdown-menu').removeClass('show');


                $(this).addClass('show');
                $(this).find('.dropdown-menu').addClass('show');
            }, function () {
            });


            $('.nav_sec').hover(function () {

            }, function () {
                $('.nav-item.dropdown').removeClass('show');
                $('.dropdown-menu').removeClass('show');
            });*/
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
