@extends('admin.layout')
@section('title', $admin_page_title)

@section ('content')

@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => $module_urls['list'],
$admin_page_title => '']])
@include('flash::message')

<div class="card-box">
    @include('admin.includes.formErrors')

    {!! Form::open(['url' => $module_urls['store'], 'class' => 'module_form', 'files' => true]) !!}
    @include($module_urls['form_file'], ['new_form' => true])
    {!! Form::close() !!}
</div>
@stop


@section('page_js')
<script src="{{ asset('/') }}thirdparty/ckeditor/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.min.js"></script>

<script type="text/javascript">
    $('.variable').tooltip();

    $('.variable').click(function(){
        $(this).tooltip('show');
    });

    var clipboard = new ClipboardJS('.variable');
    clipboard.on('success', function(e) {
        $.toast({
            text: 'Copied to clipboard!',
            icon: 'info',
        })
        e.clearSelection();
    });

    CKEDITOR.replace('email_header', {
        filebrowserImageUploadUrl: '{{ url("admin/media/editorupload") }}',
        filebrowserUploadMethod: 'form'
    });

    CKEDITOR.replace('email_footer', {
        filebrowserImageUploadUrl: '{{ url("admin/media/editorupload") }}',
        filebrowserUploadMethod: 'form'
    });
</script>
@stop
