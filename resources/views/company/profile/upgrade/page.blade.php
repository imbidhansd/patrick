<?php
    $admin_page_title = 'Upgrade Account';
?>
@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')
@include('flash::message')

<!-- Basic Form Wizard -->

<div class="card-box">
    <h1 class="text-center">{{ $page->title }}</h1>
    <div class="clearfix">&nbsp;</div>
    <div class="clearfix">&nbsp;</div>

    @include('admin.includes.formErrors')
    
    {!! $page->content !!}
    
    {!! Form::open(['class' => 'module_form']) !!}
    <div class="form-group">
        <div class="checkbox checkbox-primary checkbox-circle">
            <input type="checkbox" name="terms" id="terms" value="1" required />
            <label for="terms">I have read, understand and agree with the requirements to apply for listing with TrustPatrick.com</label>
        </div>
    </div>
    
    <button type="submit" class="btn btn-sm btn-primary">Looks Good.. Let's Go >></button>
    {!! Form::close() !!}
</div>
@endsection

@section ('page_js')
@endsection
