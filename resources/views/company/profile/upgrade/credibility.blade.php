<?php
    $admin_page_title = $page->title;
?>
@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')
@include('flash::message')

<!-- Basic Form Wizard -->
<div class="card-box">
    <div class="text-center">
        <h1>{{ $page->title }}</h1>
        <h5 class="text-primary">Don't need or want leads, but want to be a part of TrustPatrick.com? Become an Accredited Member!</h5>
    </div>

    <div class="clearfix">&nbsp;</div>
    {!! $page->content !!}
</div>
@endsection

@section ('page_js')
@endsection


