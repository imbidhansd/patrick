<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>{{ env('SITE_TITLE', 'TrustPatrick.com') }}</title>
        <style type="text/css">
            p{
                font-size: 14px;
                line-height: 22px;
            }
            p.small { font-size: 12px; line-height: 15px; }
            .custom_btn{
                color: #fff !important;
                text-decoration: none;
                background: #003e74;
                font-size: 14px;
                font-weight: 600;
                padding: 10px 15px;
                text-align: center;
            }
            table { border: none; }
            table tr td { color:#808080; font-family: 'Open Sans', sans-serif; }
        </style>
    </head>
    <body>
        @yield('header_content')
        @yield('content')
        @yield('footer_content')
    </body>
</html>