@extends('mail_content.registered_layout')

@section('content')

<p>Dear <strong> <?php echo '{{ COMPANY_NAME }}'; ?>, </strong><br />
<br />
Thanks for registering <?php echo '{{ ACCOUNT_TYPE }}'; ?> &nbsp; with TrustPatrick.com!<br />
<br />
Please confirm your registration by clicking the button below:<br />
&nbsp;</p>

<p style="text-align:center"><a class="custom_btn" href="<?php echo '{{ CONFIRMATION_LINK }}'; ?>">Confirm My Registration</a></p>

<p>Or click on the link below:<br />
<br />
<a href="<?php echo '{{ CONFIRMATION_LINK }}'; ?>"><?php echo '{{ CONFIRMATION_LINK }}'; ?> </a><br />
<br />
If the link is not live, please copy and paste the entire link into your web browser. Please make sure there are no spaces before or after the link when pasting<br />
<br />
If you did not register, just ignore this email.<br />
<br />
Thank You,<br />
<?php echo '{{ GLOBAL_ADDRESS }}'; ?></p>
@endsection