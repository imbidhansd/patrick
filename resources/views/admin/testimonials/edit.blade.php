@extends('admin.layout')
@section('title', $admin_page_title)

@section ('content')

@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => $module_urls['list'],
$admin_page_title => '']])
@include('flash::message')

<div class="card-box">
    @include('admin.includes.formErrors')
    {!! Form::model($formObj, ['method' => 'PUT', 'route' => [$module_urls['update'], $formObj->id], 'class' =>
    'module_form', 'files' => true,]) !!}
    @include($module_urls['form_file'] , ['new_form' => false])
    {!! Form::close() !!}
</div>
@stop

@section('page_js')
<!-- Plugins js -->
<script src="{{ asset('/themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>

<!-- Init js-->
<script src="{{ asset('/themes/admin/assets/js/pages/form-masks.init.js') }}"></script>

<script src="{{ asset('/') }}thirdparty/ckeditor/ckeditor.js"></script>
@stop
