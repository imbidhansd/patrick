<?php
$admin_page_title = 'Unsubscribe Emails';
?>
@extends('company.layout-without-sidebar')

@section ('content')
@include('admin.includes.breadcrumb')

<div class="card-box">
    <h3 class="text-center">Unsubscribed Successfully</h3>
    <p class="text-center font-14">You have been unsubscribed from all emails regarding TrustPatrick.com referral network.</p>
</div>
@endsection

@section ('page_js')
@endsection