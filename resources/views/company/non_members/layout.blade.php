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
    
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/non_members_page.css') }}" />
</head>
<body class="bg-grey">
    <div id="wrapper">
        <div class="container">
            <div class="clearfix">&nbsp;</div>
            
            <div class="text-center">
                <a href="{{ url('/') }}"><img src="https://s3.us-west-2.amazonaws.com/images.trustpatrick.com/trust_patrick_rn_logo_v1.png"  alt=""></a>
            </div>
            <div class="clearfix">&nbsp;</div>
            
            @yield('content')
        </div>
    </div>
    <!-- End wrapper -->

    @include ('admin.includes._js')
    @yield('page_js')
</body>
</html>
