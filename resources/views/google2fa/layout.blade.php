<!DOCTYPE html>
<html lang="en" ng-app="myModule">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="{{ asset('/') }}themes/admin/assets/images/favicon.png">
        <title>@yield('title')</title>
        @include ('admin.includes.css')
    </head>
    <body ng-app="myModule" class="white-bg">
        @include('admin.includes.2fa-header')
        <section>
            <div class="container-alt">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="wrapper-page">
                            @yield('content')

                        </div>
                        <!-- end wrapper -->
                    </div>
                </div>
            </div>
        </section>

        @include('admin.includes.footer')

        @include('admin.includes.js')
        @yield('page_js')

    </body>
</html>