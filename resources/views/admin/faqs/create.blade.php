@extends('admin.layout')
@section('title', $admin_page_title)

@section ('content')

@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => $module_urls['list'],
$admin_page_title => '']])

@include('flash::message')

<div class="card-box">
    @include('admin.includes.formErrors')

    {!! Form::open(['url' => $module_urls['store'], 'class' => 'module_form', 'files' => true]) !!}
    @include($module_urls['form_file'].'_ms', ['new_form' => true])
    {!! Form::close() !!}
</div>
@stop


@section('page_js')
@stack('form_js')
@stop
