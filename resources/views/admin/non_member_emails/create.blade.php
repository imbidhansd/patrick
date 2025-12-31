@extends('admin.layout')
@section('title', $admin_page_title)

@section ('content')

@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => $module_urls['list'],
$admin_page_title => '']])
@include('flash::message')

<div class="card-box">
    @include('admin.includes.formErrors')

    {!! Form::open(['url' => $module_urls['store'], 'class' => 'module_form', 'files' => true]) !!}
    {!! Form::hidden('email_type', 'followup_email') !!}
    @include($module_urls['form_file'], ['new_form' => true])
    {!! Form::close() !!}
</div>
@stop


@section('page_js')
@stack('formpage_js')
@stop
