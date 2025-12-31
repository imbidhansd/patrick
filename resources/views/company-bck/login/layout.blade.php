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
    @yield('page_css')
</head>

<body>

    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    @include('admin.includes.formErrors')
                    @include('flash::message')
                    <div class="card">
                        @yield('content')
                    </div>
                    <!-- end card -->
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    @include ('admin.includes._js')
    @yield('page_js')

</body>

</html>
