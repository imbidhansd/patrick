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

<body data-layout="horizontal">

    <div id="wrapper">
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
</body>

</html>
