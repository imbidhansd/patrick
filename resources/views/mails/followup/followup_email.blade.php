@extends('mails.follow_up_mail_layout')

@section('header_content')
{!! $email_header !!}
@endsection

@section('footer_content')
{!! $email_footer !!}
@endsection

@section('content')
<table align="center" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td valign="top" align="left" style="padding: 0px;">
            <table align="center" cellpadding="0" cellspacing="0" style="background: #fff; max-width: 600px; text-align: left;">
                <tr>
                    <td style="font-family: 'Open Sans', sans-serif; font-weight: 400; letter-spacing: 0.02em; line-height: 25px;">
                        {!! $email_content !!}
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endsection