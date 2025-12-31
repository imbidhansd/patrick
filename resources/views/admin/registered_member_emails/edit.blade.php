@extends('admin.layout')
@section('title', $admin_page_title)

@section ('content')

@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => $module_urls['list'],
$admin_page_title => '']])
@include('flash::message')

<div class="card-box">
    @include('admin.includes.formErrors')

    {!! Form::model($formObj, ['method' => 'PUT', 'route' => [$module_urls['update'], $formObj->id], 'class' => 'module_form', 'files' => true,]) !!}
    {!! Form::hidden('email_type', $formObj->email_type) !!}
    @include($module_urls['form_file'] , ['new_form' => false])
    {!! Form::close() !!}

</div>
@stop


@section('page_js')
@stack('formpage_js')
@stop
