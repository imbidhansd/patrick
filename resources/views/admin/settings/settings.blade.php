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
<script src="{{ asset('/') }}thirdparty/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    CKEDITOR.replace('settings[3]', {
        toolbar: [
            {name: 'document', groups: ['mode', 'document', 'doctools'], items: ['Source'] },
            {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph'], items: ['NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight']},
            {name: 'clipboard', groups: ['clipboard', 'undo']},
            {name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing']},
            { name: 'basicstyles', groups: ['basicstyles', 'cleanup'], items: ['Bold', 'Italic', 'Underline'] },
            { name: 'links', items: ['Link', 'Unlink'] },
            { name: 'colors', items: ['TextColor', 'BGColor'] },
        ]
    });
    
    CKEDITOR.replace('settings[46]', {
        toolbar: [
            {name: 'document', groups: ['mode', 'document', 'doctools'], items: ['Source'] },
            {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph'], items: ['NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight']},
            {name: 'clipboard', groups: ['clipboard', 'undo']},
            {name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing']},
            { name: 'basicstyles', groups: ['basicstyles', 'cleanup'], items: ['Bold', 'Italic', 'Underline'] },
            { name: 'links', items: ['Link', 'Unlink'] },
            { name: 'colors', items: ['TextColor', 'BGColor'] },
        ]
    });
    
    CKEDITOR.replace('settings[47]', {
        toolbar: [
            {name: 'document', groups: ['mode', 'document', 'doctools'], items: ['Source'] },
            {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph'], items: ['NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight']},
            {name: 'clipboard', groups: ['clipboard', 'undo']},
            {name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing']},
            { name: 'basicstyles', groups: ['basicstyles', 'cleanup'], items: ['Bold', 'Italic', 'Underline'] },
            { name: 'links', items: ['Link', 'Unlink'] },
            { name: 'colors', items: ['TextColor', 'BGColor'] },
        ]
    });
    
    CKEDITOR.replace('settings[48]', {
        toolbar: [
            { name: 'document', groups: ['mode', 'document', 'doctools'], items: ['Source'] },
            { name: 'links', items: ['Link', 'Unlink'] },
            { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
            { name: 'colors', items: ['TextColor', 'BGColor'] },
        ]
    });
    
    CKEDITOR.replace('settings[49]', {
        toolbar: [
            {name: 'document', groups: ['mode', 'document', 'doctools'], items: ['Source'] },
            {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph'], items: ['NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight']},
            {name: 'clipboard', groups: ['clipboard', 'undo']},
            {name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing']},
            { name: 'basicstyles', groups: ['basicstyles', 'cleanup'], items: ['Bold', 'Italic', 'Underline'] },
            { name: 'links', items: ['Link', 'Unlink'] },
            { name: 'colors', items: ['TextColor', 'BGColor'] },
        ]
    });
</script>
@stop
