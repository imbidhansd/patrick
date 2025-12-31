@extends('admin.layout')
@section('title', $admin_page_title)

@section ('content')

@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$admin_page_title => '']])
@include('flash::message')

<div class="card-box">
    <!-- end row -->

    <div class="row">
        <div class="col-sm-12">
            @include('admin.includes.formErrors')

            {!! Form::open(['url' => route('site-settings'), 'method' => 'post', 'class' => 'setting_form module_form',
            'id' => 'setting_form', 'files' => true]) !!}

            @include('admin.settings.form_partial')
            <hr />

            <button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>
            <div class="clearfix"></div>
            {!! Form::close() !!}
        </div> <!-- end col -->
    </div>
    <!-- end row -->

</div>
@stop

@section('page_js')
<!-- Summernote css -->
<link href="{{ asset('/themes/admin/assets/libs/summernote/summernote-bs4.css') }}" rel="stylesheet" type="text/css" />
<!-- Summernote js -->
<script src="{{ asset('/themes/admin/assets/libs/summernote/summernote-bs4.min.js') }}"></script>

<script type="text/javascript">
    jQuery(document).ready(function () {
    $(".summernote").summernote({
        height: 250,
        minHeight: null,
        maxHeight: null
    });
});
</script>
@stop
