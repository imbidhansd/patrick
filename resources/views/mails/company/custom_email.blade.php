@extends('mails.mail-layout')

@section ('content')

<?php /* {!! $email_header !!} */ ?>
{!! $email_content !!}
<?php /* {!! $email_footer !!} */ ?>

@stop