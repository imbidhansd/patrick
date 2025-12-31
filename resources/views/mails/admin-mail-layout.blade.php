<?php 
    $web_settings = \App\Models\Custom::getSettings();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>{{ env('SITE_TITLE', 'TrustPatrick.com') }}</title>
        <style type="text/css">
            p{
                font-size: 15px;
            }
            .custom_btn{
                color: #fff !important;
                text-decoration: none;
                background: #003e74;
                font-size: 14px;
                font-weight: 600;
                padding: 10px 15px;
                text-align: center;
            }
            .service_category_td{
                border-top-width: 1px;
                border-top-color: #eee;
                border-top-style: solid;
                padding: 5px 0px;
            }
        </style>
    </head>
    <body>
        @yield('header_content')
        @yield('content')
        @yield('footer_content')
        
        <?php /* <p style="text-align:center; padding: 30px 0 0 0;">
            <img src="{{ asset('/images/logo.png') }}" />
        </p>

        <table class="body-wrap" style="width: 100%; background-color: #f6f6f6; margin: 0; padding-bottom: 30px;"
               bgcolor="#f6f6f6">
            <tr style="margin: 0;">
                <td class="container" width="600"
                    style="vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;"
                    valign="top">
                    <div class="content" style="max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                        <table class="main" width="100%" cellpadding="0" cellspacing="0"
                               style="border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;"
                               bgcolor="#fff">

                            <tr style="margin: 0;">
                                <td class="content-wrap" style="vertical-align: top; margin: 0; padding: 20px;"
                                    valign="top">
                                    <table width="100%" cellpadding="0" cellspacing="0" style="margin: 0;">
                                        <tr style="margin: 0;">
                                            <td class="content-block"
                                                style="vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                                @yield('content')
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                             <tr style="margin: 0;">
                                <td class="alert alert-warning"
                                    style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 16px; vertical-align: top; color: #fff; font-weight: 500; text-align: center; border-radius: 0 0 3px 3px; background-color: #36404e; margin: 0; padding: 20px; color: #ffffff;"
                                    align="center" bgcolor="#2f353f" valign="top">
                                    
                                    @if (isset($web_settings['phone_number']) && $web_settings['phone_number'] != '')
                                    <a href="tel:{!! str_replace(' ', '', $web_settings['phone_number']) !!}" style="color: #ffffff; text-decoration: none;">{!! $web_settings['phone_number'] !!}</a> |
                                    @else
                                    <a href="#" style="color: #ffffff; text-decoration: none;">(000) 00 0000</a> |
                                    @endif
                                    
                                    
                                    
                                    @if (isset($web_settings['global_email']) && $web_settings['global_email'] != '')
                                    <a href="mailto:{{ $web_settings['global_email'] }}" style="color: #ffffff; text-decoration: none;">{!! $web_settings['global_email'] !!}</a>
                                    @else
                                    <a href="mailto:admin@trustpatrick.com" style="color: #ffffff; text-decoration: none;">admin@trustpatrick.com</a>
                                    @endif
                                </td>
                            </tr>
                        </table>

                    </div>
                </td>
            </tr>
        </table>  */ ?>
    </body>
</html>