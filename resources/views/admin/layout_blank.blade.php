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
        <div class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
        @include ('admin.includes.footer')
    </div>
    <!-- End wrapper -->

    @include ('admin.includes._js')
    @yield('page_js')
    @stack('additional_scripts')
</body>

</html>
