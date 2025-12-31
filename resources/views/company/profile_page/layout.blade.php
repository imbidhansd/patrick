<!DOCTYPE html>
<html lang="en">

<head>
    <?php /* <meta charset="utf-8"> */ ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
    <!-- App title -->
    <title>@yield('title')</title>
    <?php /* <meta name="robots" content="index, follow">
    <meta name="title" content="@yield('meta_title')" />
    <meta name="keywords" content="@yield('meta_keywords')" />
    <meta name="description" content="@yield('meta_description')" /> */ ?>
    
    @yield('meta')

    <!-- App css -->
    @include ('admin.includes._css')
    
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/wp-style-custom.css') }}" />
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
    @include ('company.includes.sticky_js')
    @yield('page_js')
</body>
</html>
