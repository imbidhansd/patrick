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
                font-size: 14px;
                line-height: 22px;
            }
            p.small { font-size: 11px; line-height: 15px; }
            .custom_btn{
                color: #fff !important;
                text-decoration: none;
                background: #002244;
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


        <table align="center" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td valign="top" align="left" style="padding: 0px;">
                    <table align="center" width="100%" cellpadding="0" cellspacing="0" style="background: #fff; max-width: 800px; text-align: center; border-bottom: 1px solid #ddd;">
                        <tr>
                            <td>
                                <a href="{{ url('/') }}">
                                    <img src="https://marksallpros.s3-us-west-2.amazonaws.com/logo_TrustPatrick.com_Retina.png" alt="logo"  style="max-width: 320px; padding: 30px;" />
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table align="center" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td valign="top" align="center" style="padding: 0px;">
                    <table cellpadding="0" cellspacing="0" style="background: #fff; max-width: 800px; text-align: left;">
                        <tr>
                            <td style="font-family: 'Open Sans', sans-serif; font-weight: 400; letter-spacing: 0.02em; line-height: 25px;">
                                @yield('content')
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table align="center" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td valign="top" align="left" style="padding: 0px;">
                    <table align="center" cellpadding="0" cellspacing="0" style="background: #fff; max-width: 800px; text-align: center;">
                        <tr>
                            <td align="center" style="background: #fff; border-top: 1px solid #ddd">
                                <table align="center" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="color: #808080; font-family: 'Open Sans', sans-serif; font-size: 35px; font-weight: 700; letter-spacing: 0; text-align: center;">Connect With Us</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table align="center" cellpadding="0" cellspacing="0" style="max-width: 160px;">
                                                <tr>
                                                    <td width="40">
                                                        <a href="<?php echo '{{ FACEBOOK_LINK }}'; ?>" style="border: none; display: block; font-size: 1px; height: 35px; line-height: 35px; outline: none; text-decoration: none; width: 35px;">
                                                            <img src="https://marksallpros.s3-us-west-2.amazonaws.com/user-email/facebook.png" alt="yt" style="-ms-interpolation-mode: bicubic; border: 0; display: block; font-size: 1px; height: 35px; line-height: 35px; outline: 0; text-decoration: none; width: 35px;" width="35" border="0" />
                                                        </a>
                                                    </td>
                                                    <td width="40">
                                                        <a href="<?php echo '{{ TWITTER_LINK }}'; ?>" style="border: none; display: block; font-size: 1px; height: 35px; line-height: 35px; outline: none; text-decoration: none; width: 35px;">
                                                            <img src="https://marksallpros.s3-us-west-2.amazonaws.com/user-email/twitter.png" alt="ig" style="-ms-interpolation-mode: bicubic; border: 0; display: block; font-size: 1px; height: 35px; line-height: 35px; outline: 0; text-decoration: none; width: 35px;" width="35" border="0">
                                                        </a>
                                                    </td>
                                                    <td width="40">
                                                        <a href="<?php echo '{{ INSTAGRAM_LINK }}'; ?>" style="border: none; display: block; font-size: 1px; height: 35px; line-height: 35px; outline: none; text-decoration: none; width: 35px;">
                                                            <img src="https://marksallpros.s3-us-west-2.amazonaws.com/user-email/ig.png" alt="behance" style="-ms-interpolation-mode: bicubic; border: 0; display: block; font-size: 1px; height: 35px; line-height: 35px; outline: 0; text-decoration: none; width: 35px;" width="35" border="0">
                                                        </a>
                                                    </td>
                                                    <td width="40">
                                                        <a href="<?php echo '{{ YOTUBE_LINK }}'; ?>" style="border: none; display: block; font-size: 1px; height: 35px; line-height: 35px; outline: none; text-decoration: none; width: 35px;">
                                                            <img src="https://marksallpros.s3-us-west-2.amazonaws.com/user-email/you_tube.png" alt="twitter" style="-ms-interpolation-mode: bicubic; border: 0; display: block; font-size: 1px; height: 35px; line-height: 35px; outline: 0; text-decoration: none; width: 35px;" width="35" border="0">
                                                        </a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="color: #808080; font-family: 'Open Sans', sans-serif; font-size: 13px; font-weight: normal; letter-spacing: 0.02em; line-height: 23px; text-align: center; ">
                                            <a href="<?php echo '{{ UNSUBSCRIPTION_LINK }}'; ?>" style="border: none; color: #808080; display: inline-block; font-family: 'Open Sans', sans-serif; outline: none; text-align: center; text-decoration: underline;"><span style="padding:0 15px;">Unsubscribe</span>|</a>
                                            <a href="https://trustpatrick.com/contact/" style="border: none; color: #808080; display: inline-block; font-family: 'Open Sans', sans-serif; outline: none; text-align: center; text-decoration: underline;"><span style="padding:0 15px;">Contact Us</span>|</a>
                                            <a href="https://trustpatrick.com/privacy-policy/" style="border: none; color: #808080; display: inline-block; font-family: 'Open Sans', sans-serif; outline: none; text-align: center; text-decoration: underline;"><span style="padding:0 15px;">Privacy Policy</span>|</a>
                                            <a href="https://trustpatrick.com/terms-of-use/" style="border: none; color: #808080; display: inline-block; font-family: 'Open Sans', sans-serif; outline: none; text-align: center; text-decoration: underline;"><span style="padding:0 15px;">Terms Of Use</span>|</a>
                                            <a href="https://trustpatrick.com/infringement-policy/" style="border: none; color: #808080; display: inline-block; font-family: 'Open Sans', sans-serif; outline: none; text-align: center; text-decoration: underline;"><span style="padding:0 15px;">Infringment Policy</span></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="font-family: arial,helvetica,sans-serif; color:#808080; font-size:10px; line-height: 14px;">
                                            <p>
                                                Request generated by: <?php echo '{{ REQUEST_GENERATE_LINK }}'; ?><br />
                                                Date: <?php echo '{{ DATE }}'; ?><br />
                                                URL: <?php echo '{{ URL }}'; ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <p>This followup email was sent to <?php echo '{{ EMAIL_FOOTER }}'; ?> because you recently requested to<br>find a pro for <?php echo '{{ MAIN_SERVICE_CATEGORY }}'; ?> from TrustPatrick.com.</p>
                                            <br />
                                            <p>You may unsubscribe from receiving any further communication from us at any time by clicking on the "Unsubscribe" link above.</p>
                                            <br />
                                            <p>&copy; Copyright 2019 TrustPatrick.com 3531 S Logan St, Suite D212 Englewood, CO 80113 All Rights Reserved</p>
                                            <br />
                                            <br />
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>


    </body>
</html>