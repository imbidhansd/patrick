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
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/admin/assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/admin/assets/css/icons.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/admin/assets/css/style.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/custom.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('thirdparty/parsley/parsley.css') }}" />
    @yield('page_css')
</head>


<body class="bg-transparent">

    <!-- HOME -->
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
    <!-- END HOME -->

    <!-- jQuery  -->
    <script src="{{ asset('themes/admin/assets/js/jquery.min.js') }}" type="text/javascript"></script>

    <!-- App js -->
    <script src="{{ asset('themes/admin/assets/js/jquery.core.js') }}" type="text/javascript"></script>
    <script src="{{ asset('themes/admin/assets/js/jquery.app.js') }}" type="text/javascript"></script>
    <script src="{{ asset('thirdparty/parsley/parsley.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        $(function(){
		$('.module_form').parsley();
	    });
    </script>
    @yield('page_js')
</body>

</html>
